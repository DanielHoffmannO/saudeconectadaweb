<?php

namespace Clue\React\NDJson;

use Evenement\EventEmitter;
use React\Stream\WritableStreamInterface;

class Encoder extends EventEmitter implements WritableStreamInterface
{
    private $output;
    private $options;
    private $depth;

    private $closed = false;

    public function __construct(WritableStreamInterface $output, $options = 0, $depth = 512)
    {
        if (\defined('JSON_PRETTY_PRINT') && $options & \JSON_PRETTY_PRINT) {
            throw new \InvalidArgumentException('Pretty printing not available for NDJSON');
        }
        if ($depth !== 512 && \PHP_VERSION < 5.5) {
            throw new \BadMethodCallException('Depth parameter is only supported on PHP 5.5+');
        }
        if (\defined('JSON_THROW_ON_ERROR')) {
            $options = $options & ~\JSON_THROW_ON_ERROR;
        }
        $this->output = $output;

        if (!$output->isWritable()) {
            $this->close();
            return;
        }

        $this->options = $options;
        $this->depth = $depth;

        $this->output->on('drain', array($this, 'handleDrain'));
        $this->output->on('error', array($this, 'handleError'));
        $this->output->on('close', array($this, 'close'));
    }

    public function write($data)
    {
        if ($this->closed) {
            return false;
        }

        if (\PHP_VERSION_ID < 50500) {
            $errstr = null;
            \set_error_handler(function ($_, $error) use (&$errstr) {
                $errstr = $error;
            });

            $data = \json_encode($data, $this->options);

            \restore_error_handler();
            $errno = \json_last_error();
            if (\defined('JSON_ERROR_UTF8') && $errno === \JSON_ERROR_UTF8) {
                $errstr = 'Malformed UTF-8 characters, possibly incorrectly encoded';
            } elseif ($errno !== \JSON_ERROR_NONE && $errstr === null) {
                $errstr = 'Unknown error';
            }

            if ($errno !== \JSON_ERROR_NONE || $errstr !== null) {
                $this->handleError(new \RuntimeException('Unable to encode JSON: ' . $errstr, $errno));
                return false;
            }
        } else {
            $data = \json_encode($data, $this->options, $this->depth);

            if ($data === false && \json_last_error() !== \JSON_ERROR_NONE) {
                $this->handleError(new \RuntimeException('Unable to encode JSON: ' . \json_last_error_msg(), \json_last_error()));
                return false;
            }
        }

        return $this->output->write($data . "\n");
    }

    public function end($data = null)
    {
        if ($data !== null) {
            $this->write($data);
        }

        $this->output->end();
    }

    public function isWritable()
    {
        return !$this->closed;
    }

    public function close()
    {
        if ($this->closed) {
            return;
        }

        $this->closed = true;
        $this->output->close();

        $this->emit('close');
        $this->removeAllListeners();
    }

    public function handleDrain()
    {
        $this->emit('drain');
    }

    public function handleError(\Exception $error)
    {
        $this->emit('error', array($error));
        $this->close();
    }
}

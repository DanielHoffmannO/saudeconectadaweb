<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");

    exit();
}

$pageTitle = "Consultas - Saúde Conectada";

require_once __DIR__ . 'includes/header.php';
?>

<body class="consultas-page">
    <div class="calendar-container">
        <div id='calendar'></div>
    </div>

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    {
                        title: 'Consulta com Dr. João',
                        start: new Date().toISOString().split('T')[0] + 'T14:00:00',
                        end: new Date().toISOString().split('T')[0] + 'T15:00:00',
                        backgroundColor: '#2E8B57'
                    },
                    {
                        title: 'Retorno com Dra. Ana',
                        start: new Date(Date.now() + 5 * 24*60*60*1000).toISOString().split('T')[0] + 'T10:00:00',
                        end: new Date(Date.now() + 5 * 24*60*60*1000).toISOString().split('T')[0] + 'T10:30:00',
                        backgroundColor: '#3a87ad'
                    }
                ],
                eventClick: function(info) {
                    alert('Consulta: ' + info.event.title);
                }
            });
            
            calendar.render();
        });
    </script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
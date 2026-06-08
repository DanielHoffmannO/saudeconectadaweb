const Api = (() => {
  async function request(method, path, body) {
    const token = localStorage.getItem(CONFIG.TOKEN_KEY);
    const headers = { 'Content-Type': 'application/json' };
    if (token) headers['Authorization'] = `Bearer ${token}`;

    const opts = { method, headers };
    if (body && method !== 'GET') opts.body = JSON.stringify(body);

    const res = await fetch(`${CONFIG.API_URL}${path}`, opts);

    if (res.status === 401) {
      localStorage.removeItem(CONFIG.TOKEN_KEY);
      Router.navigate('/login');
      throw new Error('Não autorizado');
    }

    if (!res.ok) {
      const err = await res.json().catch(() => ({}));
      throw new Error(err.message || `Erro ${res.status}`);
    }

    if (res.status === 204) return null;
    return res.json();
  }

  return {
    get: (path) => request('GET', path),
    post: (path, body) => request('POST', path, body),
    put: (path, body) => request('PUT', path, body),
    delete: (path) => request('DELETE', path)
  };
})();

const Router = (() => {
  const routes = {};
  const publicRoutes = ['/login', '/register'];

  function register(path, handler) {
    routes[path] = handler;
  }

  function navigate(path) {
    window.location.hash = path;
  }

  function getCurrentPath() {
    return window.location.hash.slice(1) || '/login';
  }

  function isAuthenticated() {
    return !!localStorage.getItem(CONFIG.TOKEN_KEY);
  }

  async function resolve() {
    const path = getCurrentPath();

    if (!isAuthenticated() && !publicRoutes.includes(path)) {
      navigate('/login');
      return;
    }

    if (isAuthenticated() && publicRoutes.includes(path)) {
      navigate('/consultas');
      return;
    }

    const handler = routes[path];
    if (handler) {
      Layout.render(isAuthenticated());
      const main = document.getElementById('main-content');
      if (main) main.innerHTML = await handler();
    } else {
      navigate(isAuthenticated() ? '/consultas' : '/login');
    }
  }

  function init() {
    window.addEventListener('hashchange', resolve);
    resolve();
  }

  return { register, navigate, init, resolve, getCurrentPath };
})();

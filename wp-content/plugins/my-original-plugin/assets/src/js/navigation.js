const localNav = () => {
  const navItems = document.querySelectorAll('.p-sidebar__item a');
  const currentPath = window.location.pathname;

  navItems.forEach((link) => {
    const linkPath = new URL(link.href).pathname;

    if (currentPath === linkPath) {
      link.classList.add('is-current');
      //link.parentElement.classList.add('is-current');
    }
  });
};

const globalNav = () => {
  const navItems = document.querySelectorAll('li.c-gnav__li a');
  const currentPath = window.location.pathname;
  navItems.forEach((link) => {
    const linkPath = new URL(link.href).pathname;
    if (linkPath === '/' && currentPath === '/') {
      link.parentElement.classList.add('is-current');
    } else if (linkPath !== '/' && currentPath.startsWith(linkPath)) {
      link.parentElement.classList.add('is-current');
    }
  });
};

const adaptHeight = () => {
  const height = document.querySelector('#header').offsetHeight;
  const mainContent = document.querySelector('#content');
  const body = document.body;
  if (!height || !mainContent) {
    return;
  }
  if (body.classList.contains('single')) {
    mainContent.style.paddingTop = height + 'px';
  }
};

export { localNav, globalNav, adaptHeight };

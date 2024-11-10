export default [
  {
    title: 'menu.Patients',
    icon: { icon: 'tabler-users' },
    to: 'patients',
  },
  {
    title: 'menu.Doctors',
    icon: { icon: 'tabler-users' },
    roles: ['admin'],
    to: 'doctors',
  },
  // {
  //   title: 'Авторизація',
  //   icon: { icon: 'tabler-user' },
  //   to: 'pages-authentication-login-v1',
  //   target: '_blank',
  // },
]

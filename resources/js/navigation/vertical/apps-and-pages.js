export default [
  {
    title: 'menu.Patients',
    icon: { icon: 'tabler-users' },
    roles: ['admin','doctor'],
    to: 'patients',
  },
  {
    title: 'menu.Doctors',
    icon: { icon: 'tabler-users' },
    roles: ['admin'],
    to: 'doctors',
  },
  {
    title: 'menu.PromoCodes',
    icon: { icon: 'tabler-rosette-discount-check' },
    roles: ['admin'],
    to: 'promo-codes',
  },
  {
    title: 'menu.AllPatients',
    icon: { icon: 'tabler-users' },
    roles: ['admin'],
    to: 'patients-all',
  },
  // {
  //   title: 'Авторизація',
  //   icon: { icon: 'tabler-user' },
  //   to: 'pages-authentication-login-v1',
  //   target: '_blank',
  // },
]

import MasterLayout from './components/layouts/Master';
import Home from './components/Home';
import Dashboard from './components/Dashboard';
import UserList from './components/users/Index';
import NotFound from './components/404';

const routes = [
    {
        path: '/',
        component: MasterLayout,
        children: [
            {
                path: '/',
                component: Home,
                name: 'home',
            },
            {
                path: '/dashboard',
                component: Dashboard,
                name: 'dashboard',
            },
        ],
    },
    {
        path: '/users',
        component: UserList,
        name: 'users.index',
    },
    {
        path: '**/*',
        component: NotFound,
        name: 'not_found',
    }
];

export default routes;

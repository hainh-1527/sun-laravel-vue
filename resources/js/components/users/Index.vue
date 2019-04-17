<template>
    <div>
        <button @click="userId = null" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createEdit">Add New</button>
        <table class="table table-dark">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users">
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>
                    <button @click="userId = user.id" type="button" class="btn btn-info" data-toggle="modal" data-target="#createEdit">Edit</button>
                    <button @click="deleteUser(user.id)" type="button" class="btn btn-danger">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
        <create-edit :userId="userId" :on-save="getUsers"></create-edit>
    </div>
</template>

<script>
    import CreateEdit from './CreateEdit';

    export default {
        components: {
            CreateEdit
        },

        data: function () {
            return {
                users: [],
                userId: null,
            };
        },

        created: function () {
            this.getUsers();
        },

        methods: {
            getUsers: function () {
                axios.get('api/users')
                    .then(({data}) => {
                        this.users = data.data;
                    })
                    .catch((errors) => {
                        alert(errors);
                    });
            },

            deleteUser: function (id) {
                axios.delete(`api/users/${id}`)
                    .then(() => {
                        this.getUsers();
                    })
                    .catch((errors) => {
                        alert(errors);
                    });
            },
        },
    }
</script>

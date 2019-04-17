<template>
    <div class="modal fade" id="createEdit" tabindex="-1" role="dialog" aria-labelledby="createEdit" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ userId ? 'Edit' : 'Add New' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="name" class="col-form-label">Name</label>
                            <input v-model="user.name" type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email</label>
                            <input v-model="user.email" type="text" class="form-control" id="email">
                        </div>
                        <div v-if="!userId" class="form-group">
                            <label for="password" class="col-form-label">Password</label>
                            <input v-model="user.password" type="password" class="form-control" id="password">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button @click="hideModal" type="button" class="btn btn-secondary">Cancel</button>
                    <button @click="save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const userDefault = {
        name: null,
        email: null,
        password: null,
    };

    export default {
        props: [
            'userId',
            'onSave',
        ],

        data: function () {
            return {
                user: _.cloneDeep(userDefault),
            };
        },

        methods: {
            makeSaveRequest: function () {
                if (this.userId) {
                    return window.axios.put(`api/users/${this.userId}`, this.user);
                }

                return window.axios.post('api/users', this.user);
            },

            save: function () {
                this.makeSaveRequest()
                    .then(() => {
                        this.onSave();
                        this.hideModal();
                    })
                    .catch((errors) => {
                        alert(errors);
                    });
            },

            hideModal: function () {
                $('#createEdit').modal('hide');
            },

            handleOnShowModal: function () {
                $('#createEdit').on('show.bs.modal', () => {
                    if (!this.userId) {
                        this.user = _.cloneDeep(userDefault);
                        return;
                    }

                    axios.get(`api/users/${this.userId}`)
                        .then(({data}) => {
                            this.user = data.data;
                        })
                        .catch((errors) => {
                            alert(errors);
                        });
                });
            },
        },

        mounted() {
            this.handleOnShowModal();
        },
    }
</script>

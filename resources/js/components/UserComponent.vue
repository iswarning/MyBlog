<template>
    <div class='container'>

        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" v-on:click="resetForm()">Add New User</button>
        <p>&nbsp;</p>
            <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-title"><button class='btn btn-danger btn-sm' v-on:click="resetForm()">Reset</button></div>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                                            

                        <form v-on:submit.prevent="submit(id)">
                            <div class="modal-body">
                            
                                <div v-if="success" class="row">
                                    <div class="alert alert-success col-sm-11">{{success}}</div>
                                </div>
                                <div v-else>
                                    <div class="row" v-if="errors.name">
                                        <div v-for="error in errors.name" :key="error" class='col-sm-11'>
                                            <div class="alert alert-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                    <div class="row" v-if="errors.email">
                                        <div v-for="error in errors.email" :key="error" class='col-sm-11'>
                                            <div class="alert alert-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                    <div class="row" v-if="errors.password">
                                        <div v-for="error in errors.password" :key="error" class='col-sm-11'>
                                            <div class="alert alert-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                    <div class="row" v-if="errors.role">
                                        <div v-for="error in errors.role" :key="error" class='col-sm-11'>
                                            <div class="alert alert-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>


                            <div class='form-group row'>
                                <label class='col-sm-3 col-form-label'><b>Name: </b></label>
                                <input type='text' class='form-control col-sm-8' v-model="name"/>
                            </div>                           
                            
                            <div class='form-group row'>
                                <label class='col-sm-3 col-form-label'><b>Email: </b></label>
                                <input type='email' class='form-control col-sm-8' v-model="email"/>
                            </div>

                            <div class='form-group row' v-if="id == 0">
                                <label class='col-sm-3 col-form-label'><b>Password: </b></label>
                                <input type='password' class='form-control col-sm-8' v-model="password"/>
                            </div>

                            <div class='form-group row'>
                                <label class='col-sm-3 col-form-label'><b>Role: </b></label>
                                <input type='number' class='form-control col-sm-8' v-model="role"/>
                            </div>                           

                            </div>
                            <div class="modal-footer">
                            <button type='submit' class='btn btn-primary'>Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <div class='row'>
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>                       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in this.users" :key="user.id">
                        <td>{{user.id}}</td>
                        <td>{{user.name}}</td>
                        <td>{{user.email}}</td>
                        <td>{{user.role}}</td>
                        <td v-if="user.role !== 1">
                            <button class="btn btn-primary btn-sm" v-on:click="getOne(user)" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" v-on:click="deleteOne(user.id)"><i class="fa fa-trash"></i></button>
                        </td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>


export default {
    
    data() {
        return {
            users: [],
            id: 0,
            name: '',
            email: '',
            password: '',
            role: 2,
            errors : {},
            success: ''
        }
    },

    mounted() {
        this.getAll();
    },

    methods: {
        getAll(){
            axios.get('/api/user')
                .then(res => {
                    this.users = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },

        getOne(user){
            this.resetForm();
            this.id = user.id;
            this.name = user.name;
            this.email = user.email;
            this.role = user.role;
        },

        deleteOne(id){
            axios.delete('/api/user/' + id)
                .then(res => {
                    this.getAll();
                })
                .catch(err =>{
                    console.log(err);
                });
        },

        submit(id){
            
            if(this.id == 0)
            {
                axios.post('/api/user', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    role: this.role
                })
                .then(res => {
                    this.getAll();
                    this.success = res.data.message;
                })
                .catch(err => {
                    this.errors = err.response.data.errors;
                });
            }
            else
            {
                axios.put('/api/user/' + id, {
                    name: this.name,
                    email: this.email,
                    role: this.role
                })
                .then(res => {
                    this.getAll();
                    this.success = res.data.message;
                })
                .catch(err => {
                    this.errors = err.response.data.errors;
                });
            }
        },

        resetForm(){           
            this.id = 0;
            this.name = '';
            this.email = '';
            this.password = '';
            this.role = 2;
            this.errors = {};
            this.success = '';
        }
    }
}
</script>

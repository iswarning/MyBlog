<template>
    <div class='container'>

        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" v-on:click="resetForm()">Add New Post</button>
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
                                    <div class="row" v-if="errors.title">
                                        <div v-for="error in errors.title" :key="error" class='col-sm-11'>
                                            <div class="alert alert-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                    <div class="row" v-if="errors.content">
                                        <div v-for="error in errors.content" :key="error" class='col-sm-11'>
                                            <div class="alert alert-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>

                            <div class='form-group row'>
                                <label class='col-sm-3 col-form-label'><b>Category: </b></label>

                                <select class='form-control col-sm-8' v-on:change="selectChange" :value="category_id">
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.title }}
                                    </option>
                                </select>

                            </div>

                            <div class='form-group row'>
                                <label class='col-sm-3 col-form-label'><b>Title: </b></label>
                                <input type='text' class='form-control col-sm-8' v-model="title"/>
                            </div>
                            
                            
                            
                            <div class='form-group row'>
                                <label class='col-sm-3 col-form-label'><b>Content: </b></label>
                                <textarea rows='3' class='form-control col-sm-8' v-model="content"></textarea>
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
                        <th>Title</th>
                        <th>Content</th>
                        <th>Action</th>                       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="post in this.posts" :key="post.id">
                        <td>{{post.id}}</td>
                        <td>{{post.title}}</td>
                        <td>{{post.content}}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" v-on:click="getOne(post)" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" v-on:click="deleteOne(post.id)"><i class="fa fa-trash"></i></button>
                        </td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>


export default {
    props:  {
        user_id: String
    },
    data() {
        return {
            posts: [],
            categories: [],
            category_id: 1,
            id: 0,
            title: '',
            content: '',
            errors : {},
            success: ''
        }
    },

    mounted() {
        this.getAll();
        this.getCate();
    },

    methods: {
        getAll(){
            axios.get('/api/post')
                .then(res => {
                    this.posts = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },

        selectChange: function(event){
            this.category_id = event.target.value;
        },

        getCate(){
            axios.get('/api/category')
                .then(res => {
                    this.categories = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },

        getOne(post){
            this.resetForm();
            this.id = post.id;
            this.title = post.title;
            this.content = post.content;
            this.category_id = post.category_id
        },

        deleteOne(id){
            axios.delete('/api/post/' + id)
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
                axios.post('/api/post', {
                    title: this.title,
                    content: this.content,
                    user_id: this.user_id,
                    category_id: this.category_id
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
                axios.put('/api/post/' + id, {
                    title: this.title,
                    content: this.content,
                    user_id: this.user_id,
                    category_id: this.category_id
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
            this.category_id = 1;
            this.id = 0;
            this.title = '';
            this.content = '';
            this.errors = {};
            this.success = '';
        }
    }
}
</script>

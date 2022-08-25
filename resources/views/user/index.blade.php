<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.4/css/bulma.min.css">
    <title>Document</title>
    <style>
        .custom-select {
            width: 100%;
        }

        .custom-select .dropdown-toggle {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container" id="app">
    <form action="{{ route('user.store') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="users" :value="JSON.stringify(users)">
        <div class="section" v-for="(item, index) in users" :key="index">
            <div class="field">
                <label class="label">Name</label>
                <div class="control">
                    <v-select class="custom-select" :options="names" v-model="item.name"></v-select>
                </div>
            </div>
            <div class="field">
                <label class="label">Phone</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Phone input" v-model="item.phone">
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control" v-if="index === 0">
                    <button class="button is-link">Submit</button>
                </div>
                <div class="control" v-if="index === 0">
                    <button class="button is-link" @click.prevent="addUser">New row</button>
                </div>
                <div class="control" v-if="index !== 0">
                    <button class="button is-danger" @click.prevent="deleteUser(index)">Delete row</button>
                </div>
            </div>
        </div>
    </form>
    @if(session()->has('message'))
        <div class="notification is-primary">
            {{ session()->get('message') }}
        </div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@0.12.0/dist/axios.min.js"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#app',
        data: {
            users: [
                {
                    name: '',
                    phone: ''
                }
            ],
            names: []
        },
        created() {
            axios.get('https://cors-anywhere.herokuapp.com/https://dev-giraffe.myshopify.com/admin/customers.json', {
                headers: {
                    Authorization: 'Basic ODUyMmU0OTc3MzI4MTdmYThhMzZkNGEzNmI2NzQ5ZGE6YmIyYWRjOTNiODAxNjk4MWFmMmQwZjQ1YzRkM2EzOGQ='
                }
            }).then((data) => {
                data.data.customers.forEach((item) => {
                    if (item.first_name && item.last_name) {
                        this.names.push(item.first_name + ' ' + item.last_name)
                    }
                })
            });
        },
        methods: {
            addUser() {
                this.users.push({
                    name: '',
                    phone: ''
                })
            },
            deleteUser(index) {
                this.users.splice(index, 1)
            }
        }
    })
</script>
</body>
</html>

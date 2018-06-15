<template>
    <div class="shipping-root">
        <div class="panel panel-default shipping-block">

            <!-- Верхнее меню (хэдер)-->
            <div class="panel-heading">
                <a v-if="!auth" href="/login">Войти</a>&nbsp;&nbsp;&nbsp;
                <a v-if="!auth" href="/register">Регистрация</a>&nbsp;&nbsp;&nbsp;
                <a v-if="auth" href="/logout" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                    Выйти
                </a>
            </div>

            <!-- Основной контент-->
            <div class="shipping-content">
                <h1 class="text-center">Список покупок</h1>

                <List
                        :auth="auth"
                        :all_data="all_data"
                        :parent_message="message"
                        :trigger="trigger"
                        v-on:save_data="saveData"
                        v-on:delete_data="deleteData"
                />

            </div>

        </div>
    </div>
</template>

<script>
    import List from './components/List.vue' ;

    export default {
        components: {
            List
        },
        data: function () {
            return {
                auth: false,
                all_data: '',
                list_id: -1,
                message: '',
                trigger: false
            }
        },
        created: function () {
            this.getData();
        },
        methods: {
            getData: function (data) {
                let vm = this;

                axios.post('/get',{'list_id': vm.list_id})
                    .then(function (response) {
                        vm.all_data = response.data;
                        vm.list_id = response.list_id;
                        vm.auth = true;
                    })
                    .catch(function (error) {
                        vm.message = 'Не удалось получить данные с сервера (возможно, Вы не зарегистрированы или отсутствует подключение к интернету).';
                    });
            },
            saveData: function (data) {
                let vm = this;
                let list_id;

                if( this.all_data.list_id ) {
                    list_id = this.all_data.list_id;
                } else {
                    list_id = -1 ;
                }
                data['list_id'] = list_id;

                axios.post('/save', data)
                    .then(function (response) {
                        vm.message = response.data.message;
                        vm.trigger = !vm.trigger;
                    })
                    .catch(function (error) {
                        vm.message = 'Не удалось загрузить ваши данные на сервер (возможно, Вы не зарегистрированы или отсутствует подключение к интернету).';
                    });
            },
            deleteData: function (data) {
                let vm = this;
                let list_id;

                if( this.all_data.list_id ) {
                    list_id = this.all_data.list_id;
                } else {
                    list_id = -1 ;
                }
                axios.post('/delete', {'list_id': list_id})
                    .then(function (response) {
                        vm.message = response.data.message;
                        vm.trigger = !vm.trigger;
                    })
                    .catch(function (error) {
                        vm.message = 'Не удалось удалить ваши данные на сервере (возможно, Вы не зарегистрированы или отсутствует подключение к интернету).';
                    });
            }

        }
    }
</script>

<style lang="scss">

    .shipping-root {
        padding: 0;
        background-color: #eee;
        min-height: 100vh;
    }

    .shipping-block {
        max-width: 700px;
        margin: 0 auto;
    }

    .shipping-content {
        max-width: 500px;
        margin: 0 auto;
        padding: 10px;
    }
</style>
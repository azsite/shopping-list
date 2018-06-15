<template>

    <div>

        <!-- Поле ввода новых пунктов списка-->
        <ItemAdd
                v-model="newItem"
                @addItem="addItem"
                @keydown.enter="addItem"
        />

        <!-- Список продуктов-->
        <ul v-if="items.length" class="shopping-items">

            <!-- Элементы списка продуктов-->
            <Item
                    v-for="item in items"
                    :key="item.id"
                    :item="item"
                    @check-off="checkOff"
                    @check-on="checkOn"
                    @remove="removeItem"
            />

            <!-- Конец списка - общая сумма (фактическая)-->
            <li class="shopping-item shopping-total bottom-items clearfix ">
                <div class="shopping-column-1">
                    <button v-if="check_all" @click="check_all = !check_all" class="btn btn-default btn-xs shopping-btn">
                        <span>&#10004;</span>
                    </button>
                    <button v-else @click="check_all = !check_all" class="btn btn-default btn-xs shopping-btn">
                        <span style="opacity: 0;">&#10004;</span>
                    </button>
                </div>
                <div class="shopping-column-2">
                    <span class="shopping-text">
                        <span>Фактический бюджет:</span>
                        <span>{{ total }}</span>
                    </span>
                </div>
            </li>

            <!-- Конец списка - общая сумма (запланированная)-->
            <li class="shopping-item shopping-total bottom-items clearfix ">
                <div class="shopping-column-2">
                    <span class="shopping-text">
                        <span>Запланированный бюджет:</span>
                        <span>{{ total_plan }}</span>
                    </span>
                    <button v-if="plan_mode" @click="offPlanMode" class="btn btn-default btn-sm shopping-mode-btn">
                        Установить
                    </button>
                    <button v-else @click="onPlanMode" class="btn btn-default btn-sm shopping-mode-btn">
                        Сбросить
                    </button>
                </div>
            </li>

        </ul>

        <p v-else>
            В списке нет записей.
        </p>

        <p v-show="message_on" class="shopping-message text-center">
            {{message}} <br>
            <button @click="closeMessage" class="btn btn-default btn-xs shopping-btn">
                Закрыть
            </button>
        </p>

        <!-- Сохранение и удаление списка -->
        <button @click="saveList" class="btn btn-default btn-sm shopping-btn" title="Этот список будет сохранен в ваш аккаунт (на удаленный сервер)">
            Сохранить список
        </button>
        <button @click="deleteList" class="btn btn-default btn-sm shopping-btn" title="Этот список будет удален с вашего устройства и из вашего аккаунта (с удаленного сервера)">
            Удалить список
        </button>

    </div>

</template>

<script>

    import ItemAdd from './ItemAdd.vue' ;
    import Item from './Item.vue' ;

    let nextItemId = 1 ;

    export default {
        props: {
            auth: {
                type: Boolean,
                required: true
            },
            all_data: {
                type: Object,
                required: true
            },
            parent_message: {
                type: String,
            },
            trigger: {
                type: Number,
            }
        },
        components: {
            ItemAdd,
            Item
        },
        data: function() {
            return {
                message_on: false,
                message: '',
                newItem: '',
                total_plan: 0,
                plan_mode: true,
                check_all: true,
                list_id: -1,
                items: []
            }
        },
        computed: {
            total: function () {
                let summ = 0;
                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i]['check']){
                        summ += (this.items[i]['price'] * this.items[i]['count']);
                    }
                }
                return summ ;
            },
            surcharge: function () {
                return  !this.plan_mode && ((this.total - this.total_plan) > 0) ;
            }
        },
        methods: {
            addItem() {
                const trimText = this.newItem.trim();
                if (trimText) {
                    this.items.push({
                        id: nextItemId++,
                        check: true,
                        text: trimText,
                        count: '',
                        price: '',
                        edit_text: false
                    });
                    this.newItem = '';
                }
            },
            removeItem(id) {
                this.items = this.items.filter( function(item) {
                    return item.id !== id
                })
            },
            checkOff(id) {
                for (var i = 0; i < this.items.length; i++) {
                    if (this.items[i]['id'] === id){
                        this.items[i]['check'] = false;
                    }
                }
            },
            checkOn(id) {
                for (let i = 0; i < this.items.length; i++) {
                    if (this.items[i]['id'] === id){
                        this.items[i]['check'] = true;
                    }
                }
            },
            offPlanMode() {
                this.total_plan = this.total;
                this.check_all = false;
                this.plan_mode = false;
            },
            onPlanMode() {
                this.total_plan = 0;
                this.plan_mode = true;
            },
            closeMessage() {
                this.message_on = false;
            },
            saveList() {
                if (this.items.length){
                    this.$emit('save_data', this.$data);
                }
            },
            deleteList() {
                if (this.items.length){
                    this.message = '';
                    this.newItem = '';
                    this.total_plan = 0;
                    this.plan_mode = true;
                    this.check_all = true;
                    this.items = {};
                    this.$emit('delete_data');
                }
            }
        },
        watch: {
            check_all: function (check_all) {
                for (var i = 0; i < this.items.length; i++) {
                    this.items[i]['check'] = check_all;
                }
            },
            surcharge: function (surcharge) {
                if (surcharge){
                    this.message = 'Вы превысили планируемый бюджет!!!';
                    this.message_on = true;
                }
            },
            parent_message: function (parent_message) {
                if (parent_message){
                    this.message = parent_message;
                    this.message_on = true;
                }
            },
            trigger: function (trigger) {
                this.message_on = true;
            },
            all_data: function(all_data){
                this.list_id = all_data.list_id;
                this.total_plan = all_data.budget;
                this.items = all_data.all_data.map(function(data) {
                    return {
                        id: nextItemId++,
                        check: true,
                        text: data.name,
                        count: data.count,
                        price: data.price,
                        edit_text: false
                    };
                });
            }
        }
    }

</script>

<style lang="scss">
    .shopping-items {
        padding-left: 0;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    .shopping-total .shopping-text {
        font-size: 1.2em;
    }
    .shopping-mode-btn {
        display: inline-block;
        font-size: 1.0em;
        maggin-left: 15px;
    }
    .shopping-message {
        font-size: 1.2em;
        padding: 5px;
        background-color: #f88;
        border: 1px solid #000;
        border-radius: 4px;
        color: #fff;
    }
</style>
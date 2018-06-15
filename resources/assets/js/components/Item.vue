<template>

    <li class="shopping-item clearfix">

        <!-- Чекбокс-->
        <div class="shopping-column-1">
            <button v-if="item.check" @click="$emit('check-off', item.id)" class="btn btn-default btn-xs shopping-btn">
                <span>&#10004;</span>
            </button>
            <button v-else @click="$emit('check-on', item.id)" class="btn btn-default btn-xs shopping-btn">
                <span style="opacity: 0;">&#10004;</span>
            </button>
        </div>

        <div class="shopping-tools">

            <!-- Поле количества-->
            <input
                    type="text"
                    class="form-control input-xs shopping-count"
                    placeholder="N"
                    title="Количество"
                    v-model="item.count"
            >

            <!-- Поле цены-->
            <input
                    type="text"
                    class="form-control input-xs shopping-price"
                    placeholder="Цена"
                    title="Цена"
                    v-model="item.price"
            >

            <!-- Кнопка удаления-->
            <button @click="$emit('remove', item.id)" class="btn btn-default btn-xs shopping-btn">
                &#10006;
            </button>
        </div>

        <!-- Текстовое поле-->
        <b v-if="!item.edit_text"  v-on:click="item.edit_text = !item.edit_text">
            {{ item.text }}
        </b>

        <!-- Поле редактирования текста-->
        <div v-else class="input-group shopping-column-2">
            <input
                    type="text"
                    class="form-control input-xs shopping-text"
                    placeholder="Цена"
                    v-model="item.text"
            >

            <!-- Кнопка подтверждения изменений-->
            <span v-on:click="item.edit_text = false" class="input-group-addon shopping-btn-add">
                &#10004;
            </span>
        </div>

    </li>

</template>

<script>
    export default {
        props: {
            item: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style lang="scss">

    .shopping-item {
        list-style-type: none;
        margin-top: 4px;
        padding: 3px 4px 3px 40px;
        border-top: 1px solid #eee;
    }
    .shopping-item.bottom-items {
        border-top: 1px solid #bbb;
        background-color: #f8f8f8;
    }
    .shopping-item.bottom-items:last-child {
        border-bottom: 1px solid #bbb;
    }
    .shopping-column-1{
        display: inline-block;
        margin-left: -30px;
        float: left;
    }
    .shopping-column-2{
        /*display: inline-block;*/
    }
    .shopping-text {
        /*padding-left: 5px;*/
    }
    .shopping-tools{
        display: inline-block;
        margin-left: 5px;
        float: right;
    }
    .shopping-price{
        display: inline-block;
        width: 65px;
    }
    .shopping-count{
        display: inline-block;
        width: 50px;
    }
    .shopping-btn {
        display: inline-block;
        font-size: 1.2em;
    }

</style>
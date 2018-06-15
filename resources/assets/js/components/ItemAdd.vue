<template>

    <!-- Поле ввода новых пунктов списка-->
    <div class="input-group shopping-input">
        <input
                type="text"
                class="form-control"
                placeholder="Новый пункт"
                :value="value"
                v-on="inputListeners"
        >
        <span v-on="inputListeners" class="input-group-addon shopping-btn-add">&#10010;</span>
    </div>

</template>

<script>
    export default {
        props: {
            value: {
                type: String,
                default: ''
            }
        },
        computed: {
            inputListeners: function () {
                let vm = this;
                return Object.assign({},
                    this.$listeners,
                    {
                        click: function (event) {
                            if(event.target.classList.contains('shopping-btn-add')) {
                                vm.$emit('addItem', vm.new_item)
                            }
                        },
                        input: function (event) {
                            vm.$emit('input', event.target.value)
                        }
                    }
                )
            }
        }
    }
</script>

<style lang="scss" scoped>
    .shopping-input {
        max-width:500px;
        margin: 4px auto;
    }
    .shopping-btn-add:hover{
        cursor: pointer;
    }
</style>

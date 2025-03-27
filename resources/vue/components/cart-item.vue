<template>
    <div class="cart-item">
        <button class="button" @click.prevent="$emit('remove')" v-if="item.deletable">🗑️</button>
        <div class="cart-item__info">
            <p class="cart-item__name" v-html="item.label"></p>
            <p class="cart-item__quantity">Quantité : {{ item.quantity }}</p>
        </div>
        <p class="cart-item__price" v-html="item.price"></p>
    </div>
</template>

<script>
export default {
    props: ['item']
}
</script>

<style scoped>
.cart-item {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 12px 0;
}

.cart-item + .cart-item {
    border-top: 1px solid #E9E9E9;
}

.cart-item__info {
    flex-grow: 1;
}
</style>
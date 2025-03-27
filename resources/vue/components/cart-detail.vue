<template>
    <div class="cart-detail">
        <p class="cart-detail__label" v-html="item.label"></p>
        <p class="cart-detail__price" v-html="item.price"></p>
    </div>
</template>

<script>
export default {
    props: ['item']
}
</script>

<style scoped>
.cart-detail {
    display: flex;
    gap: 12px;
    align-items: center;
}

.cart-detail__label {
    flex-grow: 1;
}
</style>
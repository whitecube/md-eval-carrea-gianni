<template>
    <div class="product">
        <p class="product__name title">{{ product.name }}</p>
        <p class="product__category">{{ product.category }}</p>
        <button class="button" @click.prevent="$emit('increment')">➕</button>
        <button class="button" @click.prevent="$emit('decrement')">➖</button>
    </div>
</template>

<script>
export default {
    props: ['product']
}
</script>

<style scoped>
.product {
    padding: 24px;
    border-radius: 6px;
    border: 1px solid #E9E9E9;
}

.product__category {
    margin-bottom: 16px;
}
</style>
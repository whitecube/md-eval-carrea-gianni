<template>
    <div class="cart">
        <div class="cart__products">
            <product v-for="product, index in products"
                :key="index"
                :product="product"
                @increment="increment(product)"
                @decrement="decrement(product)" />
        </div>

        <div class="cart__side">
            <p class="title">Panier</p>
            <div class="cart__items">
                <p v-if="! items.length" class="cart__empty">Le panier est vide</p>
                <cart-item v-for="item in items"
                    :key="item.id"
                    :item="item"
                    @remove="remove(item)" />
            </div>
            <div class="cart__detail" v-if="detail.length">
                <cart-detail v-for="item in detail"
                    :key="item.id"
                    :item="item" />
            </div>

            <!-- Display discount line if totalDiscount > 0 and no margin -->
            <div class="cart__discount" v-if="hasDiscount">
                <p class="title">Remises :</p>
                <p>-{{ discountValue }}</p>
            </div>

            <div class="cart__discount_label" v-if="hasDiscount">
                <p>{{ discountLabels }}</p>
            </div>

            <div class="cart__total">
                <p class="title">Total</p>
                <p v-html="discountedTotal"></p>
            </div>
            
            <!-- Display margin message if hasMargin is true -->
            <p v-if="hasMargin" class="cart__margin">(Marge pour grossistes comprise)</p>

            
        </div>
    </div>
</template>

<script>
import Product from '../components/product.vue';
import CartItem from '../components/cart-item.vue';
import CartDetail from '../components/cart-detail.vue';

export default {
    props: ['products', 'receipt'],
    components: { Product, CartItem, CartDetail },

    data() {
        return {
            items: [],
            detail: [],
            total: 0,
            discountedTotal: 0,
            discountValue: 0,
            url: null,
            hasMargin: false,
            hasDiscount: false,
            discountLabels: [],
        }
    },

    mounted() {


        this.items = this.receipt.items;
        this.detail = this.receipt.detail;
        this.total = this.receipt.total;
        this.discountedTotal = this.receipt.discountedTotal;
        this.discountValue = this.receipt.discountValue;
        this.discountLabels = this.receipt.discountLabels;
        this.url = this.receipt.route;
        this.hasMargin = this.receipt.hasMargin;
        this.hasDiscount = this.receipt.hasDiscount;
    },

    methods: {

        increment(product) {
            let item = this.items.find(item => item.product === product.id);

            if (!item) {
                return this.update({ id: product.id, quantity: 1 });
            }

            this.update({ id: product.id, line: item.line, quantity: (item.quantity + 1) });
        },

        decrement(product) {
            let item = this.items.find(item => item.product === product.id);

            if (!item) {
                return;
            }

            this.update({ id: product.id, line: item.line, quantity: Math.max(0, item.quantity - 1) });
        },

        remove(item) {
            if (!item) {
                return;
            }

            this.update({ id: item.product, line: item.line, quantity: 0 });
        },

        update(data) {
            window.axios.post(this.url, data).then(response => {
                this.items = response.data.items;
                this.detail = response.data.detail;
                this.total = response.data.total;
                this.url = response.data.route;
                this.discountLabels = response.data.discountLabels;
                this.discountedTotal = response.data.discountedTotal;
                this.discountValue = response.data.discountValue;
                this.hasMargin = response.data.hasMargin;
                this.hasDiscount = response.data.hasDiscount;
            });
        }
    }
}
</script>

<style scoped>
.cart {
    display: flex;
    align-items: flex-start;
    gap: 32px;
    margin: 60px 0;
}

.cart__products {
    width: 65%;
    display: grid;
    gap: 12px;
    grid-template-columns: repeat(3, 1fr);
    flex-shrink: 0;
}

.cart__side {
    flex-grow: 1;
    padding: 24px;
    border-radius: 6px;
    border: 1px solid #E9E9E9;
}

.cart__empty {
    font-style: italic;
    padding: 12px 0;
}

.cart__detail {
    padding: 12px 0;
    border-top: 1px solid #E9E9E9;
}

.cart__total {
    display: flex;
    justify-content: space-between;
    border-top: 1px solid #1f1b30;
    padding-top: 12px;
    font-weight: bold;
}

.cart__discount {
    display: flex;
    justify-content: space-between;
    border-top: 1px solid #E9E9E9;
    padding-top: 12px;
    font-weight: bold;
    color: #d9534f; /* Rouge pour indiquer une réduction */
}

.cart__discount_label {
    display: flex;
    border-top: 1px solid #E9E9E9;
    padding-top: 8px;
    color: #d9534f; /* Rouge pour indiquer une réduction */
}

@media screen and (max-width: 1024px) {
    .cart {
        flex-direction: column;
    }

    .cart__products,
    .cart__side {
        width: 100%;
    }
}

.cart__margin {
    color: #28a745; /* Green color for the margin message */
    font-size: 0.9rem;
    margin-bottom: 8px;
    font-style: italic;
}
</style>

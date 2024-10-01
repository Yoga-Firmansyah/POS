
import { createApp } from 'vue'
import App from './App.vue'
import { createPinia } from 'pinia'
import router from './routes'
import VueNumberFormat from '@coders-tm/vue-number-format'
import VueBarcode from '@chenfengyuan/vue-barcode';

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(VueNumberFormat)
app.component(VueBarcode.name, VueBarcode);

app.mixin({

    methods: {

        moneyFormat(number) {
            let reverse = number.toString().split('').reverse().join(''),
                thousands = reverse.match(/\d{1,3}/g)
            thousands = thousands.join('.').split('').reverse().join('')
            return thousands
        },

    }
})
app.mount('#app')

<template>
<div style="text-align: center;">
  <v-autocomplete :items="items" v-model='item' :get-label='getLabel' :min-len='0' @update-items='update' :component-item='tpl' @item-selected="itemSelected" @item-clicked="itemClicked" :input-attrs="{name: 'input-test', id: 'v-my-autocomplete'}"></v-autocomplete>
  <p>Selected item:</p>
  <pre>{{ item }}</pre>
</div>
</template>

<script>
import Autocomplete from 'v-autocomplete'
import 'v-autocomplete/dist/v-autocomplete.css'
import tpl from './TplItem.vue'
import Animals from '../animals.js'
export default {
    name: 'auto-complete',
    components: {'v-autocomplete': Autocomplete},
    data () {
        return {
            itemsApi: [],
            item: {id: 9, name: 'Lion', description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.'},
            items: [],
            tpl: tpl
        }
    },
    methods: {
        itemSelected (item) {
            console.log('Selected item!', item)
        },
        itemClicked (item) {
            console.log('You clicked an item!', item)
        },
        getLabel (item) {
            if (item) {
                return item.name
            }
            return ''
        },
        update (text) {
            this.items = Animals.filter((item) => {
                return (new RegExp(text.toLowerCase())).test(item.name.toLowerCase())
            })
        }
    }
}
</script>

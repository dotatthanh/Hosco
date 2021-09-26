
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

<div class="container" id="app">
    <auto-complete :items="items" v-model="item" :get-label="getLabel" :component-item='template' @update-items="updateItems"></auto-complete>
</div>

<script type="text/javascript">
    import utils from '../js/utils.js'
    import Item from '../js/components/Item.vue'

    Vue.component('item', {
        props: {
            item: { required: true }
        },
        template: "<div>{{item}}</div>"
    });

    Vue.component('autoComplete', {
        props: {
            componentItem: { default: () => Item },
            minLen: { type: Number, default: utils.minLen },
            wait: { type: Number, default: utils.wait },
            value: null,
            getLabel: {
                type: Function,
                default: item => item
            },
            items: Array,
            autoSelectOneItem: { type: Boolean, default: true },
            placeholder: String,
            inputClass: {type: String, default: 'v-autocomplete-input'},
            disabled: {type: Boolean, default: false},
            inputAttrs: {type: Object, default: () => {return {}}},
            keepOpen: {type: Boolean, default: false}
        },
        template: '<div class="v-autocomplete">\n' +
            '    <div class="v-autocomplete-input-group" :class="{\'v-autocomplete-selected\': value}">\n' +
            '      <input type="search" v-model="searchText" v-bind="inputAttrs" \n' +
            '            :class="inputAttrs.class || inputClass"\n' +
            '            :placeholder="inputAttrs.placeholder || placeholder"\n' +
            '            :disabled="inputAttrs.disabled || disabled"\n' +
            '            @blur="blur" @focus="focus" @input="inputChange"\n' +
            '            @keyup.enter="keyEnter" @keydown.tab="keyEnter" \n' +
            '            @keydown.up="keyUp" @keydown.down="keyDown">\n' +
            '    </div>\n' +
            '    <div class="v-autocomplete-list" v-if="show">\n' +
            '      <div class="v-autocomplete-list-item" v-for="item, i in internalItems" @click="onClickItem(item)"\n' +
            '           :class="{\'v-autocomplete-item-active\': i === cursor}" @mouseover="cursor = i">\n' +
            '        <div :is="componentItem" :item="item" :searchText="searchText"></div>\n' +
            '      </div>\n' +
            '    </div>\n' +
            '  </div>',
        data: function () {
            return {
                search: '',
                results: []
            }
        },
        methods: {
            getSearchData(){
                this.results = [];
                if(this.search.length > 0){
                    let config = {
                        headers:{
                            'Content-Type':'application/json',
                            'Accept':'application/json'
                        },
                        params: {productCode: this.search},
                    }

                    axios.get("{{ route('product.index') }}",config).then(response => {
                        console.log(response.data.result.data);
                        this.results = response.data.result.data;
                    });
                }
            },
            inputChange () {
                this.showList = true
                this.cursor = -1
                this.onSelectItem(null, 'inputChange')
                utils.callUpdateItems(this.searchText, this.updateItems)
                this.$emit('change', this.searchText)
            },
            updateItems () {
                this.$emit('update-items', this.searchText)
            },
            focus () {
                this.$emit('focus', this.searchText)
                this.showList = true
            },
            blur () {
                this.$emit('blur', this.searchText)
                setTimeout( () => this.showList = false, 200)
            },
            onClickItem(item) {
                this.onSelectItem(item)
                this.$emit('item-clicked', item)
            },
            onSelectItem (item) {
                if (item) {
                    this.internalItems = [item]
                    this.searchText = this.getLabel(item)
                    this.$emit('item-selected', item)
                } else {
                    this.setItems(this.items)
                }
                this.$emit('input', item)
            },
            setItems (items) {
                this.internalItems = items || []
            },
            isSelectedValue (value) {
                return 1 == this.internalItems.length && value == this.internalItems[0]
            },
            keyUp (e) {
                if (this.cursor > -1) {
                    this.cursor--
                    this.itemView(this.$el.getElementsByClassName('v-autocomplete-list-item')[this.cursor])
                }
            },
            keyDown (e) {
                if (this.cursor < this.internalItems.length) {
                    this.cursor++
                    this.itemView(this.$el.getElementsByClassName('v-autocomplete-list-item')[this.cursor])
                }
            },
            itemView (item) {
                if (item && item.scrollIntoView) {
                    item.scrollIntoView(false)
                }
            },
            keyEnter (e) {
                if (this.showList && this.internalItems[this.cursor]) {
                    this.onSelectItem(this.internalItems[this.cursor])
                    this.showList = false
                }
            },
        },
        created () {
            utils.minLen = this.minLen
            utils.wait = this.wait
            this.onSelectItem(this.value)
        },
        watch: {
            items (newValue) {
                this.setItems(newValue)
                let item = utils.findItem(this.items, this.searchText, this.autoSelectOneItem)
                if (item) {
                    this.onSelectItem(item)
                    this.showList = false
                }
            },
            value (newValue) {
                if (!this.isSelectedValue(newValue) ) {
                    this.onSelectItem(newValue)
                    this.searchText = this.getLabel(newValue)
                }
            }
        }
    })


    const app = new Vue({
        el: '#app'
    });
</script>

    <style>
        .v-autocomplete {
            position: relative;
        }
        .v-autocomplete .v-autocomplete-list {
            position: absolute;
        }
        .v-autocomplete .v-autocomplete-list .v-autocomplete-list-item {
            cursor: pointer;
        }
        .v-autocomplete .v-autocomplete-list .v-autocomplete-list-item.v-autocomplete-item-active {
            background-color: #f3f6fa;
        }
    </style>


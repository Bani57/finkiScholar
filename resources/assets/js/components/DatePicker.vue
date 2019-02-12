<template>
    <div class="ui calendar" ref="calendar">
        <div class="ui action input left icon" style="width: 100%">
            <i class="calendar icon"></i>
            <input type="text" :placeholder="this.placeholder" :name="this.name">
            <button class="ui icon button" @click="deleteDate">
                <i class="trash alternate outline icon"></i>
            </button>
        </div>
    </div>
</template>

<script type="text/javascript">
    import moment from 'moment'
    import 'semantic-ui-calendar/dist/calendar.js'
    import 'semantic-ui-calendar/dist/calendar.css'

    export default {
        name: 'DatePicker',
        props: {
            placeholder: {
                type: String,
                required: false
            },
            name: {
                type: String,
                required: false
            },
            default: {
                type: [Object, Date],
                required: false
            },
            minDate: {
                type: [Object, Date],
                required: false
            },
            maxDate: {
                type: [Object, Date],
                required: false
            }
        },
        methods: {
            deleteDate() {
                this.$emit('date', null)
            },
        },
        mounted() {
            var vm = this;
            $(this.$refs.calendar).calendar({
                popupOptions: {
                    observeChanges: false
                },
                type: 'date',
                ampm: false,
                minDate: (this.minDate) ? new Date(moment(this.minDate).add(5, 'minutes')) : null,
                maxDate: (this.maxDate) ? new Date(moment(this.maxDate).subtract(5, 'minutes')) : null,
                onChange: function (date, text, mode) {
                    vm.$emit('date', date)
                },
                formatter: {
                    datetime(date, settings) {
                        if (!date) return '';
                        return moment(date).format('DD/MM/YYYY')
                    }
                }
            });
            $(this.$refs.calendar).calendar('set date', new Date(moment(this.default)), true, false)
        },
        beforeDestroy: function () {
        },
        watch: {
            default(val) {
                $(this.$refs.calendar).calendar('set date', new Date(moment(val)), true, false)
            },
        }
    }
</script>
<style scoped>
</style>

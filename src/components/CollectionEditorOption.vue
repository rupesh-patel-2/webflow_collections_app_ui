<template>
    <div class="relative py-8">
        <List :columns="cols" :items="listItems" class="table-auto">
            <template v-slot:[fieldName]="{ item }">
                <div class="text-base font-semibold text-gray-900 dark:text-white"> {{ item['project-details'] }}</div>
                <div class="text-sm font-normal text-gray-500 dark:text-gray-400"> {{ item['project-details'] }} </div>
            </template>

        </List>
    </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    data() {
        return {
            message: 'Hello!'
        }
    },
    computed: {
        greeting() {
            return this.message + '!'
        }
    },
    mounted() {
        this.greeting // type: string
    }
})

let cols = [
    {
        'key': 1,
        'label': 'label 1',

    },
    {
        'key': 2,
        'label': 'label 2',

    }
];

let listItems =
    [{ "key": "project-details", "label": "Project Details" }];

const fieldName = '#cell(fieldName)';


</script>
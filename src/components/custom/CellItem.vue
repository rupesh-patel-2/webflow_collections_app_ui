<template>
    <div>
        <span v-if="editMode">
            <input type="text" v-model="editValue" @blur="(event) => { blurHandler(event) }">
        </span>

        <span v-else @click="editMode = true">
            {{ displayValue }}
        </span>

    </div>
</template>

<script setup lang="ts">
let editMode = ref<boolean>(false);

const emits = defineEmits(["editEvent"]);

const props = defineProps<{
    item_type?: string,
    column_key: string,
    item_value: any,
    item_id: string,
}>();

let editValue = ref<string>(props.item_value);
let displayValue = ref<string>(props.item_value);
const blurHandler = (event: any) => {
    editMode.value = false;
    displayValue.value = editValue.value;
    emits('editEvent', {
        item_id: props.item_id,
        old_value: props.item_value,
        new_value: editValue.value,
        column_key: props.column_key
    });
}

</script>
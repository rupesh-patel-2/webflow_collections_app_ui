<template>
    <div>
        <span v-if="editMode">
            <input type="text" v-model="editValue" @blur="(event) => { blurHandler(event) }">
        </span>

        <span v-else>
            <span v-if="props.item_type == 'Option'">
                <select v-model="editValue" @change="(event) => { blurHandler(event) }">
                    <option v-for="option in validations.options" :value=option.id>
                        {{ option.name }}
                    </option>
                </select>
            </span>
            <span v-else @click="editClickHandler">
                {{ displayValue }}
            </span>
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
    validations?: any,
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

const editClickHandler = () => {
    let editableTypes = ['PlainText', 'Option'];
    console.log(props.item_type);
    if (props.item_type != undefined && editableTypes.includes(props.item_type)) {
        console.log('ifff');
        editMode.value = true;
    }
}

</script>
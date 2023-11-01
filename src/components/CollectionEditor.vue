<template>
    <div class="relative py-8">

        {{ editMessage }}

        <div v-if="editedCount > 0">
            <span>
                Edited Items: {{ editedCount }}
            </span>

            <button type="button"
                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 my-5 ml-2"
                @click="updateCollectionList()">
                Save
            </button>

        </div>

        <CustomList :columns="props.checkedItems" :items="listItems" class="table-auto" @editEvent="editHandler">


        </CustomList>
    </div>
</template>

<script setup lang="ts">
import ajax from "@/accessories/ajax";
import CustomList from "@/components/custom/CustomList.vue";

const props = defineProps({
    selectedSiteId: { type: Number, default: 0 },
    selectedCollectionId: { type: Number, default: 0 },
    checkedItems: { type: Array },
})

let listCols = ref<Array<any>>([]);
let listItems = ref<Array<any>>([]);
let editHistory = ref<Array<any>>([]);
let editMessage = ref<string>('');
let editedData: any = {};
let editedCount = ref<number>(0);

// watch works directly on a ref
watch(() => props.selectedCollectionId, (first, second) => {
    console.log(
        "Watch props.selected function called with args:",
        first,
        second
    );
    collectionFields();
    collectionList();
    editedData = {};
    editedCount.value = 0;
});


// Selected Collection's Field Type
async function collectionFields() {
    let aj = new (ajax as any)();
    let data = [
        {
            endPoint: "collections/" + props.selectedCollectionId,
            params: props.selectedCollectionId,
        },
    ];

    let result = await aj.post("/CallApi.php", data);


    if (result.status == 200) {
        console.log(result.data.fields);

        let colData = [];
        for (let i in result.data.fields) {
            colData.push({ key: result.data.fields[i]['slug'], label: result.data.fields[i]['displayName'] });
        }
        listCols.value = colData;

    } else {
        return false;
    }
}



// Selected Collection's Item List
async function collectionList() {

    let aj = new (ajax as any)();
    let data = [
        {
            endPoint: "collections/" + props.selectedCollectionId + "/items",
            params: props.selectedCollectionId,
        },
    ];

    let result = await aj.post("/CallApi.php", data);


    if (result.status == 200) {
        let itemData = [];
        for (let i in result.data.items) {
            result.data.items[i].fieldData.item_id = result.data.items[i].id;
            itemData.push(result.data.items[i].fieldData);
        }
        listItems.value = itemData;

    } else {
        return false;
    }


}

function editHandler(data: any) {
    console.log("Edited", data.item_id);
    editHistory.value.push(data);
    editedData[data.item_id] = editedData[data.item_id] ? editedData[data.item_id] : {};
    editedData[data.item_id][data.column_key] = data.new_value;
    editedCount.value = Object.keys(editedData).length;
}

async function updateCollectionList() {
    console.log('collection_id', props.selectedCollectionId);
    console.log("Edited Data from update", editedData);
    let updateData = {};
    let aj = new (ajax as any)();
    let index = 1;
    for (let i in editedData) {
        updateData = { "isArchived": false, "isDraft": false, fieldData: editedData[i] };
        let data = [
            {
                method: 'PATCH',
                endPoint: "collections/" + props.selectedCollectionId + "/items/" + i,
                params: JSON.stringify(updateData),
            },
        ];

        editMessage.value = "Editing" + index + " Record Out of " + editedCount.value;

        let result = await aj.post("/CallApi.php", data);

        if (result.status == 200) {
            console.log("Update result" + result.data);


        } else {
            editMessage.value = "Something Went Wrong at " + i + " th record";
            return false;
        }

    }
    editMessage.value = "All Records Updated Successfully!";
    window.setTimeout(function () {
        editMessage.value = '';
    }, 3000);
    console.log('Updated Data pushed', updateData);

}



</script>
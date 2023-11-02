<script setup lang="ts">
import CollectionEditor from '@/components/CollectionEditor.vue'
import { SelectDropdown, Dropdown } from '@/components/crud'
import ajax from "@/accessories/ajax";

// useRoute, useHead, and HelloWorld are automatically imported. See vite.config.ts for details.
const route = useRoute()

useHead({
  title: route.meta.title,
  meta: [
    {
      property: 'og:title',
      content: route.meta.title,
    },
    {
      name: 'twitter:title',
      content: route.meta.title,
    },
  ],
});

let selectedSiteId = ref<number>(0);
let selectedCollectionId = ref<number>(0);
let sites = ref<Array<any>>([]);
let collections = ref<Array<any>>([]);
let cols = ref<Array<any>>([]);

// Site Ids list
async function listOfSites() {
  try {
    let aj = new (ajax as any)();
    let data = [
      {
        endPoint: "sites",
      },

    ];

    let result = await aj.post("/CallApi.php", data);

    if (result.status == 200) {
      console.log(result.data.sites);
      sites.value = [];
      let SiteDetails = [];
      for (let i in result.data.sites) {
        let st = result.data.sites[i];
        SiteDetails.push({ value: st.id, label: st.displayName })
      }
      console.log(SiteDetails);
      sites.value = SiteDetails;
    } else {
      return false;
    }
  } catch (error) {
    console.log("Site List error:--" + error);
  }
}
listOfSites();

// Selected Site Id's collections list
async function siteCollection(siteId: any) {
  selectedSiteId.value = siteId.new;

  let aj = new (ajax as any)();
  let data = [
    {
      endPoint: "sites/" + selectedSiteId.value + "/collections",
      params: selectedSiteId.value,
    },
  ];

  let result = await aj.post("/CallApi.php", data);
  if (result.status == 200) {
    console.log(result);

    collections.value = [];
    let collectionDetails = [];
    for (let i in result.data.collections) {
      let st = result.data.collections[i];
      collectionDetails.push({ value: st.id, label: st.displayName })
    }
    console.log(collectionDetails);
    collections.value = collectionDetails;
  }


}


function collectionChangeHandler(change: any) {
  selectedCollectionId.value = change.new;
  collectionFields(selectedCollectionId.value);
}

// Selected Collection's Field Type
async function collectionFields(selectedCollectionId: any) {
  let aj = new (ajax as any)();
  let data = [
    {
      endPoint: "collections/" + selectedCollectionId,
      params: selectedCollectionId,
    },
  ];

  let result = await aj.post("/CallApi.php", data);


  if (result.status == 200) {
    let colData = [];
    for (let i in result.data.fields) {
      colData.push({ key: result.data.fields[i]['slug'], label: result.data.fields[i]['displayName'], item_type: result.data.fields[i]['type'] });
    }
    cols.value = colData;

  } else {
    return false;
  }
}

let checkedItems: any = ref<Array<any>>([]);
  
function colsToDisplay(event: any) {

  if (event.checked) {
    checkedItems.value.push({
      'key': event.id,
      'label': event.value,
    });
    console.log("checkedItems", checkedItems.value);
  } else {
    // console.log('splice', checkedItems.value.indexOf(checkedItems.value));

    console.log("find index id", checkedItems.value.splice(checkedItems.value.indexOf(event.id)));

    // checkedItems.value.splice(checkedItems.value.indexOf(checkedItems.value));
    // console.log("final checkedItems", checkedItems.value);

  }
}

function clearCheckedValues() {
  checkedItems.value = [];

}

</script>

<template>
  <div class="container mx-auto mt-5">

    <div class="flex items-end">
      <SelectDropdown :options="sites" name="site" label="Select Site" @change="siteCollection" class="w-1/3">
      </SelectDropdown>

      <SelectDropdown :options="collections" name="collections" @change="collectionChangeHandler"
        label="Select Collection" class="w-1/3 ml-2">
      </SelectDropdown>

      <Dropdown :options="cols" label="Select Columns to Display " class="w-1/3 ml-2" @allCheck="colsToDisplay"
        @clearCheckedValues="clearCheckedValues">
      </Dropdown>



    </div>

    <CollectionEditor :selectedSiteId=selectedSiteId :selectedCollectionId=selectedCollectionId
      :checkedItems=checkedItems></CollectionEditor>

  </div>
</template>

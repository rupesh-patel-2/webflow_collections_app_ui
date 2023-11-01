<template>
 
       <!--<component v-for="(field,index) in fields" :ref="(ele:any) => { fieldRefs[index] = ele }" :is="getField(field.comp)"></component> --> 
        <div class="space-y-4" >
            <slot name="fields" :refs="fieldRefs">
            </slot>
        </div>
        <slot name="actions" :save="save">
            <div class="space-y-4 mt-2" >
                <button type="submit"  @click="save"
                    class="text-white w-72 justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 
                    focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 
                    dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"> 
                    Save 
                </button>
            </div>
        </slot>
        <!-- <slot name="actions" :back="back">
            <div class="space-y-4 mt-2" >
                <button type="submit"  @click="back"
                    class="text-white w-72 justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 
                    focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 
                    dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"> 
                    Back 
                </button>
            </div>
        </slot> -->
        <h5 id="drawer-label" class="inline-flex items-center text-sm font-semibold text-gray-500 uppercase dark:text-gray-400">
            <slot name="deleteHeader">
                Delete item
            </slot>
        </h5>
        <slot name="svg">
            <svg class="w-10 h-10 mt-8 mb-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </slot>
        <h3 class="mb-6 text-lg text-gray-500 dark:text-gray-400">
            <slot name="deleteSubHeader">
                Are you sure you want to delete this item?
            </slot>
        </h3>
        <div class="space-y-4 mt-2" >
            <slot name="actionsDelete" :deletep="deletep">
                <!-- <button type="submit" @click="deletep"
                    class="text-white w-full justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 
                    focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 
                    dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"> 
                    Delete 
                </button> -->
                <button type="submit"  @click="deletep"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-900">
                    Yes, I'm sure
                </button>
                <button type="submit" @click="closeDrawer"
                    class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                    data-modal-toggle="delete-product-modal">
                    No, cancel
                </button>
            </slot>
        </div>
        
</template>

<script setup lang="ts">
    import { useSlots,onBeforeUpdate } from 'vue'
    const slots = useSlots()
    let fieldRefs = ref<Array<any>>([])

        // let test = save

    onBeforeUpdate(() => {
        // fieldRefs.value.forEach((element:any) => {
        //     element.value = ''
        //     console.log(element.value,'fieldRefs.value')
        // });
        fieldRefs.value = []
    })

    const emit = defineEmits(['save', 'deletep', 'closeDrawer', 'back'])

    /** Technique to load the components from variable dynamically */
    /** this function will return the imported component if the name of the component passed  */
    function getField( test:string ){
        return  eval(test);
    }
    let valid:boolean = false;
    let data:any = {};
    let save = () => {
        if(isValid()){
            generateData();
            emit('save',data);
        }
        fieldRefs.value = [];
        data = {};
    };
    let back = () => {
            emit('back',data);
    };
    let deletep = () => {
        if(isValid()){
            generateData();
            emit('deletep',data);
        }
    }

    let closeDrawer = () => {
        emit('closeDrawer',data);
    }
    let test = save

    const isValid = () => {
        valid = true;
        fieldRefs.value.forEach((refr) => {
            if(refr.isValid){
                if(!refr.isValid()){
                    valid = false;
                }   
            }
        })
        return valid;
    }

    const generateData = () => {
        fieldRefs.value.forEach((refr) => {
            if(refr.props.name && refr.value) {
                data[refr.props.name] = refr.value;
            }
        })
    }
</script>
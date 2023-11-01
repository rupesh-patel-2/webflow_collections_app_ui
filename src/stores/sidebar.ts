export type SideBarItem = {
    title: string,
    icon?:string,
    children?:Array<SideBarItem>,
    route?:string
}

export const SideBarStore = defineStore('sideBar', () => {
    const items = ref<Array<SideBarItem>>([]);
    let titles = [];
   
    const addAfter = (item:SideBarItem,title:string) => {
        if(items.value.filter( ( it ) => { it.title == item.title}).length == 0 ){
            for(let i = 0 ; i <= items.value.length ; i++){
                if(items.value[i].title == title){
                    items.value.splice(i,0,item);
                    break; 
                }
            }
        }
    }

    const add = (item:SideBarItem,title?:string) => {
        if(title){
            for(let i = 0 ; i < items.value.length ; i++){
                if(items.value[i].title == title){
                    items.value[i].children = items.value[i].children ? items.value[i].children : [];
                    // console.log(items.value[i].children , "The children");
                    if(items.value[i].children?.filter( ( it ) => { return it.title == item.title}).length == 0 ){
                        items.value[i].children?.push(item);
                    }
                    break;
                }
            }
        } else {
            
            if(items.value.filter( ( it ) => {  return it.title == item.title}).length == 0 ){ 
                items.value.push(item); 
            }
            
        }
       
    }

    const remove = (title:string, parentTitle?:string) => {
        
    }

    return { items , addAfter, add , remove };
})


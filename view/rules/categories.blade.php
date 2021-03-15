<script>
    let categoriesStore = new webix.DataCollection({

    });
    function categoryRuleSave(){

        let checkedItems = [];
        let checkedIds = $$('category_tree').getChecked();


        for (let i = 0; i < checkedIds.length; i++) {
            let checkedId = checkedIds[i];

            let checkedItem = $$('category_tree').getItem(checkedId);

            checkedItems.push(checkedItem);
        }
        updateDataCollection(categoriesStore, checkedItems);
        this.getTopParentView().hide();
    }

    webix.ui({
        view:"window",
        id:"win_category_tree",
        move:true,
        width:500,
        height:500,
        position:"center",
        modal:true,
        head:{
            view:"toolbar", margin:5, cols:[
                {
                    view: "label", label: "@lang('EvocmsDiscounts::main.category_tree_caption')"
                },
                {
                    view: "icon",
                    icon: "wxi-check",
                    tooltip: "@lang('EvocmsDiscounts::main.save_changes')",
                    click: categoryRuleSave                },
                {
                    view: "icon",
                    icon: "wxi-close",
                    tooltip: "@lang('EvocmsDiscounts::main.close_without_save')",
                    click: function () {
                        this.getTopParentView().hide();
                    }
                }
            ]
        },
        body:{
            rows:[
                {cols:[
                        { view:"button", type:"icon", icon:"mdi mdi-file-tree",  label:"@lang('EvocmsDiscounts::main.category_tree_open')", width:160,  click:function(){$$("category_tree").openAll()} },
                        { view:"button", type:"icon", icon:"mdi mdi-pine-tree",  label:"@lang('EvocmsDiscounts::main.category_tree_hide')", width:160,  click:function(){$$("category_tree").closeAll()} },
                    ]},
                {
                    view:"treetable",
                    label:"@lang('EvocmsDiscounts::main.category_rule_caption')",
                    id: "category_tree",
                    url: "{!! $moduleUrl !!}&action=rule-categories-load",
                    autoheight:true,
                    autowidth:true,
                    minHeight: 300,
                    columns:[
                        { id:"id", header:"ID", css:{"text-align":"left"}, width:40},
                        { id:"value", header:"@lang('EvocmsDiscounts::main.category_tree_table_caption')", width:400, template:"{common.space()}{common.icon()}{common.treecheckbox()}{common.folder()}#value#" }
                    ],
                    activeTitle:true
                },
                {
                    cols:[
                        { view:"button", value: "@lang('EvocmsDiscounts::main.save')", type:"form", click: categoryRuleSave},
                        { view:"button", value: "@lang('EvocmsDiscounts::main.close')", click: function (id,event) {
                            this.getTopParentView().hide();
                        }}
                    ]}
            ]},
    });





    let categoriesRule = {

        getTypes:function(){
            return ['product'];
        },

        getCaption:function () {
            return '@lang('EvocmsDiscounts::main.category_rule_caption')';
        },

        getDescriptionForTable:function (products) {
            let output =  '<b>'+this.getCaption()+': </b>';
            let names = [];
            for(let i =0;i<products.length;i++){
                names.push(products[i].value);
            }
            output+=names.join(', ');
            return output;
        },

        addRuleView:function (ruleInnerId,rule) {

            let viewId = ruleInnerId+"_categories_view";
            let listId = ruleInnerId+"_categories_list";


            updateDataCollection(categoriesStore,rule);

            let obj = {
                id: viewId,
                rows:[
                    {
                        view: "list",
                        id: listId,
                        template: "#value#",

                        autoheight:true,
                        maxHeight: 200,

                    },
                    {
                        view: "button",
                        label: "@lang('EvocmsDiscounts::main.category_tree_show')",

                        on: {
                            onItemClick: function () {

                                $$('category_tree').uncheckAll();

                                let categories = categoriesStore.serialize();

                                console.log(categories)

                                for(let key in categories){
                                    let category = categories[key];

                                     $$("category_tree").checkItem(category.id);

                                }
                                $$("win_category_tree").show()

                            }
                        }

                    },
                ]
            };

            $$(ruleInnerId).addView(obj);


            $$(listId).sync(categoriesStore);

            return categoriesStore;

        },
        getData(store){
            return store.serialize();
        }

    };


    rulesManager.addRule('categories',categoriesRule);

</script>
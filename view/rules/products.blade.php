<script>
    let productsSore = new webix.DataCollection({

    });


    webix.ui({
        view: "window",
        id: "win_product_tree",
        move: true,
        width: 500,
        height: 500,
        position: "center",
        modal: true,
        head: {
            view: "toolbar", margin: 5, cols: [
                {
                    view: "label", label: "@lang('EvocmsDiscounts::main.rule_products_tree_caption')"
                },
                {
                    view: "icon",
                    icon: "wxi-close",
                    click: "$$('win_product_tree').hide();",
                    tooltip: "@lang('EvocmsDiscounts::main.close_without_save')"
                }
            ]
        },
        body: {
            rows: [
                {
                    cols: [
                        {
                            view: "toolbar", cols: [
                                {
                                    view: "text",
                                    css: "search_product_tree_field",
                                    id: "search_product_tree_field",
                                    placeholder: "@lang('EvocmsDiscounts::main.rule_products_filter_by_name_caption')",

                                },
                                {
                                    view: "button",
                                    type: "icon",
                                    icon: "wxi-filter",
                                    label: "",
                                    tooltip: "@lang('EvocmsDiscounts::main.rule_products_tree_search')",
                                    autowidth: true,
                                    click:function () {
                                        $$("product_tree_selected").selectAll();


                                        var ids = $$("product_tree_selected").getSelectedId();
                                        var search = $$("search_product_tree_field").getValue();

                                        $$("product_tree").clearAll();
                                        $$("product_tree").load("{!! $moduleUrl !!}&action=rule-products-search-products&" + "&checked=" + ids + "&search=" + search);


                                    }
                                }
                            ]
                        },
                        {
                            view: "toolbar", cols: [
                                {
                                    view: "template", align: "middle,middle", template: "@lang('EvocmsDiscounts::main.rule_products_tree_products_with_sale_caption')", height: 10
                                },
                            ]
                        },
                    ]
                },
                {
                    cols: [
                        {
                            view: "list",
                            label: "@lang('EvocmsDiscounts::main.rule_products_products_list')",
                            id: "product_tree",
                            autowidth: true,
                            template:"#title#",
                            drag: "move"
                        },
                        {
                            view: "list",
                            label: "@lang('EvocmsDiscounts::main.rule_products_selected_products')",
                            id: "product_tree_selected",
                            template:"#title#",
                            autowidth: true,
                            maxHeight: 400,
                            drag: "move"
                        },
                    ]
                },
                {
                    cols: [
                        {
                            view: "button",
                            value: "@lang('EvocmsDiscounts::main.save')",
                            type: "form",
                            click:function () {
                                updateDataCollection(productsSore,$$('product_tree_selected').serialize());
                                $$("win_product_tree").hide()
                            }
                        },
                        {
                            view: "button",
                            value: "@lang('EvocmsDiscounts::main.close')",
                            click:function () {
                                $$("win_product_tree").hide()
                            }
                        }
                    ]
                },

            ]
        },
    })

    let productsRule = {

        getTypes:function(){
            return ['product'];
        },

        getCaption:function () {
            return '@lang('EvocmsDiscounts::main.rule_products_caption')';
        },

        getDescriptionForTable:function (products) {
            let output =  '<b>'+this.getCaption()+': </b>';
            let names = [];
            for(let i =0;i<products.length;i++){
                names.push(products[i].title);
            }
            output+=names.join(', ');
            return output;
        },

        addRuleView:function (ruleInnerId,rule) {

            let viewId = ruleInnerId+"_products_view";

            let listId = ruleInnerId+"_products_list";

            updateDataCollection(productsSore,rule);

            let obj = {
                id:viewId,

                rows:[
                    {
                        view: "list",
                        id: listId,
                        template: "#title#",

                        autoheight:true,
                        maxHeight: 200,


                    },
                    {
                        view: "button",
                        label: "@lang('EvocmsDiscounts::main.rule_products_show_list')",

                        on: {
                            onItemClick: function () {
                                $$('win_product_tree').show();

                                $$('product_tree').clearAll();
                                $$('product_tree_selected').clearAll();

                                let items = productsSore.serialize();
                                for(let key in items){
                                    let item = items[key];


                                    $$('product_tree_selected').add(item);

                                }
                            }
                        }

                    },
                ]
            };

            $$(ruleInnerId).addView(obj);


          $$(listId).sync(productsSore);

          return productsSore;

        },
        getData(store){
            return store.serialize();
        }

    };


    rulesManager.addRule('products',productsRule);

</script>
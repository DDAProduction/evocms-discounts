<script>

    function resizeEditWindow() {
        let maxHeight = window.innerHeight - 100;

        let bodyHeight = $$('win_discount_scrollview').getBody().$height;

        if(bodyHeight>maxHeight){
            bodyHeight = maxHeight;
        }

        $$('win_discount_scrollview').define('minHeight',bodyHeight);
        $$('win_discount_scrollview').resize();

    }

    let discountEntityTypes = {
        product:{
            getCaption:function () {
                return '@lang('EvocmsDiscounts::main.discount_entity_type_product_caption')';
            },
            getForm:function () {
                return [
                    {
                        cols:[
                            {
                                view: "text", name: "title", placeholder: "@lang('EvocmsDiscounts::main.discount_entity_form_title')", labelPosition: "top"
                            },
                            {
                                view: "checkbox",name: "active", labelRight: "@lang('EvocmsDiscounts::main.discount_entity_form_active')", labelWidth:10, width:150,
                            }
                        ]
                    },
                    {
                        cols: [
                            {
                                view: "text",name: "discount_value", label: "@lang('EvocmsDiscounts::main.discount_entity_form_discount_value')", labelPosition: "top"
                            },
                            {
                                view: "select",name: "discount_type", label: "@lang('EvocmsDiscounts::main.discount_entity_form_discount_type')", labelPosition: "top", options: [
                                    {id: "amount", value: "@lang('EvocmsDiscounts::main.discount_type_amount')"},
                                    {id: "percent", value: "@lang('EvocmsDiscounts::main.discount_type_percent')"},
                                ]
                            }
                        ]
                    },
                ];
            }
        },
        cart:{
                getCaption:function () {
                    return '@lang('EvocmsDiscounts::main.discount_entity_type_cart_caption')';
                },
                getForm:function () {
                    return [
                        {
                            cols:[
                                {
                                    view: "text", name: "title", placeholder: "@lang('EvocmsDiscounts::main.discount_entity_form_title')", labelPosition: "top"
                                },
                                {
                                    view: "checkbox",name: "active", labelRight: "@lang('EvocmsDiscounts::main.discount_entity_form_active')", labelWidth:10, width:150,
                                },
                                {
                                    view: "checkbox",name: "exclude_sales", labelRight: "@lang('EvocmsDiscounts::main.discount_entity_form_exclude_sales')", labelWidth:10, width:250,
                                },

                            ]
                        },
                        {
                            cols: [
                                {
                                    view: "text",name: "discount_value", label: "@lang('EvocmsDiscounts::main.discount_entity_form_discount_value')", labelPosition: "top"
                                },
                                {
                                    view: "select",name: "discount_type", label: "@lang('EvocmsDiscounts::main.discount_entity_form_discount_type')", labelPosition: "top", options: [
                                        {id: "amount", value: "@lang('EvocmsDiscounts::main.discount_type_amount')"},
                                        {id: "percent", value: "@lang('EvocmsDiscounts::main.discount_type_percent')"},
                                    ]
                                }
                            ]
                        },
                    ];
                }

        }

    };

    let discountRulesStores = {};
    let discountApplyStores = {};




    function addWindowWithForm(type) {

        var applies = appliesManager.getAppliesForDiscount(type);
        let appliesOptions = [];
        for(let key in applies){

            let apply = applies[key];


            appliesOptions.push({
                id:key,
                value:apply.getCaption()
            })
        }


        let discount = discountEntityTypes[type];


        webix.ui({
            id: "win_discount",
            view:"window",

            head:{
                view:"toolbar", margin:5, cols:[
                    {
                        view:"label", label: discount.getCaption()
                    },
                    {
                        view:"icon", icon:"wxi-close", tooltip:"@lang('EvocmsDiscounts::main.close_without_save')",
                        click:function () {
                            $$('win_discount').close();
                        },

                    }
                ]
            },

            position:"center",
            modal:true,

            body:{
                view:"scrollview",
                id:"win_discount_scrollview",
                scroll:'auto',



                // width:window.outerWidth-50,

                width: 800,


                body: {


                    rows:[

                        {
                            id: "discount_form",
                            view: "form",
                            elements: discount.getForm()
                        },
                        {
                            template: "@lang('EvocmsDiscounts::main.rules')", type: "section"
                        },
                        {
                            id: "discount_rules", label: "@lang('EvocmsDiscounts::main.close_without_save')", rows: [],
                        },
                        {
                            cols: [
                                {},
                                {
                                    id: "discount_rules_select",
                                    view: "select",
                                    options:[],
                                },
                                {
                                    id: "discount_rules_add",
                                    view: "button",
                                    label:"@lang('EvocmsDiscounts::main.add')",
                                    click:function () {
                                        addRule(type,$$("discount_rules_select").getValue(), {});
                                        updateAvailableRules(type);
                                    }
                                },
                                {}
                            ],
                        },

                        {
                            template: "@lang('EvocmsDiscounts::main.apply')", type: "section"
                        },
                        {

                            id: "discount_applies_select",
                            view: "select",
                            options:appliesOptions,
                            on:{
                                onChange: function (newValue) {
                                    changeDiscountApply(newValue, {});
                                }
                            }
                        },
                        {
                            id: "discount_apply",  rows: [],
                        },

                        {
                            cols:[
                                {
                                    view:"button",
                                    value:"@lang('EvocmsDiscounts::main.save')",
                                    click:saveDiscount,
                                },
                                {
                                    view:"button",
                                    value:"@lang('EvocmsDiscounts::main.cancel')",
                                    click:function () {
                                        $$('win_discount').hide();
                                    }
                                }
                            ],
                        },
                        {
                            height:20,

                        }

                    ]
                }


            }

        });
    }



    function saveDiscount() {
        let values = $$('discount_form').getValues();

        values['rules'] = {};
        for(let ruleId in discountRulesStores){

            let ruleStore = discountRulesStores[ruleId];
            let rule = rulesManager.getRule(ruleId);

            let ruleValue = rule.getData(ruleStore);

            if(ruleValue === null){
                continue;
            }

            values['rules'][ruleId] = rule.getData(ruleStore);
        }

        let applyId = $$("discount_applies_select").getValue();

        let apply = appliesManager.getApply(applyId);


        values.apply = {};
        values.apply[applyId] = apply.getData('discount_apply');


        $$("discounts").updateItem(values.id,values);
        $$('win_discount').hide()
     }

    function changeDiscountApply(applyId,applyValues) {


        let oldApplyNode = $$('discount_apply').getChildViews();
        if(oldApplyNode.length){
            $$("discount_apply").removeView(oldApplyNode[0].config.id);
        }

        let apply = appliesManager.getApply(applyId);

        discountApplyStores = apply.addApplyView('discount_apply',applyValues);

        resizeEditWindow();

    }


    function editDiscount(item) {

        discountRulesStores = {};
        discountApplyStores = {};

        let type = item.type;
        addWindowWithForm(type);


        let form = {};
        Object.assign(form, item)

        delete form.rules;
        delete form.apply;


        $$('discount_form').setValues(form);


        for(let ruleId in item.rules){
            addRule(type,ruleId,item.rules[ruleId]);
        }

        let applyValues = null;
        let applyId = null;

        for(let key in item.apply){
            applyValues = item.apply[key];
            applyId = key;
        }

        $$("discount_applies_select").setValue(applyId);

        changeDiscountApply(applyId,applyValues);

        updateAvailableRules(type);
        $$('win_discount').show();

        resizeEditWindow();
    }


</script>
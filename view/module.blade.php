@php
    /** @var $rules \EvolutionCMS\EvocmsDiscounts\Rules\Rule[] */
@endphp
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('EvocmsDiscounts::main.caption')</title>

    <link rel="stylesheet" href="/manager/media/style/common/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="/assets/modules/evocms-discounts/codebase/webix.min.css">
    <link rel="stylesheet" href="/assets/modules/evocms-discounts/codebase/skins/skin.css">

    <style>
        .fa, .fas {
            font-weight: 900 !important;
        }
        .fa, .far, .fas {
            font-family: "Font Awesome 5 Free" !important;
        }

    </style>
</head>
<body>

<script src="/assets/modules/evocms-discounts/codebase/webix.min.js"></script>
<script src="/assets/modules/evocms-discounts/utils.js"></script>

<div id="container"></div>
<div id="pager"></div>

<script>


    let discountTypeAmount = 'amount';
    let discountTypePercent = 'percent';

    let discountTypes = [
        {
            id:discountTypeAmount,
            value:"@lang('EvocmsDiscounts::main.discount_type_amount')",
        },
        {
            id:discountTypePercent,
            value:"@lang('EvocmsDiscounts::main.discount_type_percent')",
        }
    ];


    let appliesManager = {

        applies:{},

        addApply:function (applyId,rule) {
            this.applies[applyId] = rule;
        },
        getApply:function (applyId) {
            return this.applies[applyId];
        },

        getApplies:function () {
            return this.applies;
        },

        getAppliesForDiscount:function (discountType) {
            let applies = [];

            for (let applyId in this.applies){

                let apply = this.getApply(applyId);


                if(apply.getTypes().includes(discountType)){
                    applies[applyId] = apply;
                }
            }
            return applies;
        },
        getFirstApplyId:function (discountType) {
            let applies = this.getAppliesForDiscount(discountType);

            for(let key in applies){
                return key;
            }
            return null;
        }


    };


    let rulesManager = {

        rules:{},

        addRule:function (ruleId,rule) {
            this.rules[ruleId] = rule;
        },
        getRule:function (ruleId) {
            return this.rules[ruleId];
        },
        getRulesForDiscount:function (discountType) {
            let rules = [];

            for (let ruleId in this.rules){

                let rule = this.getRule(ruleId);


                if(rule.getTypes().includes(discountType)){
                    rules[ruleId] = rule;
                }
            }
            return rules;
        },

        getRules:function () {

            return this.rules;
        }

    };


    function updateAvailableRules(discountType) {

        let ruleStores = discountRulesStores;

        let rules = rulesManager.getRulesForDiscount(discountType);
        let options = [];

        for(let ruleId in rules){

            if(ruleStores[ruleId]){
                continue;
            }

            let rule = rulesManager.getRule(ruleId);

            options.push({
                id:ruleId,
                value:rule.getCaption()
            })

        }
        $$("discount_rules_select").define("options", options);
        $$("discount_rules_select").refresh();

    }

    function updateDataCollection(DataCollection,data) {
        DataCollection.clearAll();

        for(let key in data){
            let el = data[key];
            DataCollection.add(el);
        }
    }

    function addRuleOwner(discountType,ruleId,caption){
        let viewId = "rule_"+ruleId;
        let viewInnerId = "rule_inner_"+ruleId;

        let object = {
            view: "fieldset",
            id:viewId,
            label: caption ,
            body: {
                rows: [
                    {
                        cols: [
                            {
                                id:viewInnerId,
                                rows:[]
                            },
                            {
                                align: "bottom,middle",
                                body: {
                                    view: "icon",
                                    icon: "wxi-trash",
                                    click:function () {

                                        $$("discount_rules").removeView(viewId);
                                        delete discountRulesStores[ruleId];
                                        updateAvailableRules(discountType);
                                        resizeEditWindow();
                                    }

                                },
                            },
                        ]
                    }
                ]
            }
        };
        $$("discount_rules").addView(object);
        return viewInnerId;
    }


    function addRule(discountType,ruleId,ruleValues) {


        let ruleManager = rulesManager.getRule(ruleId);

        let ruleInnerId = addRuleOwner(discountType,ruleId,ruleManager.getCaption());
        let ruleStore = ruleManager.addRuleView(ruleInnerId,ruleValues);

        discountRulesStores[ruleId] = ruleStore;
        resizeEditWindow();

    }


    function addDiscount(type) {
        let applyId = appliesManager.getFirstApplyId(type);

        let obj = {
            type:type,
        };
        obj.apply = {};
        obj.apply[applyId] = null;


        console.log(obj)

        $$('discounts').add(obj,0)
    }



    function removeDiscount() {
        var selectedId = $$('discounts').getSelectedId();

        if(selectedId === undefined){
            webix.alert('@lang('EvocmsDiscounts::main.selected_anything')');
            return;
        }
        $$('discounts').remove(selectedId)
    }

</script>


@foreach($rules as $rule)
    @include($rule->getView())
@endforeach

@foreach($applies as $apply)
    @include($apply->getView())
@endforeach


@include('EvocmsDiscounts::discounts_edit')

<script>



    webix.ui({
        container: "container",
        rows:[
            {
                view:"template", type:"header", template:"@lang('EvocmsDiscounts::main.caption')"
            },

            { view:"toolbar", id:"mybar", elements:[
                    {
                        view:"button",
                        type:"icon",
                        icon:"fas fa-list",
                        tooltip:"@lang('EvocmsDiscounts::main.toolbar_create_discount_for_product')",
                        autowidth:true,
                        click: function () {
                            addDiscount('product')
                        }
                    },
                    {
                        view:"button",
                        type:"icon",
                        icon:"fas fa-cart-plus",
                        tooltip:"@lang('EvocmsDiscounts::main.toolbar_create_discount_for_cart')",
                        autowidth:true,
                        click: function () {
                            addDiscount('cart')
                        }
                    },
                    { view:"button", type:"icon", icon:"wxi-pencil",  label:"@lang('EvocmsDiscounts::main.toolbar_edit')", width:110, click:function () {

                            var selectedId = $$('discounts').getSelectedId();

                            if(selectedId === undefined){
                                webix.alert('@lang('EvocmsDiscounts::main.selected_anything')');
                                return;
                            }

                            let item = $$('discounts').getItem(selectedId);

                                editDiscount(item)

                        }  },
                    { view:"button", type:"icon", icon:"wxi-radiobox-blank", tooltip:"@lang('EvocmsDiscounts::main.toolbar_reload')", autowidth:true },
                    {
                        view:"button",
                        type:"icon",
                        icon:"wxi-trash",
                        label:"@lang('EvocmsDiscounts::main.toolbar_remove')",
                        width:110,
                        css:"webix_danger",
                        click:"removeDiscount"
                    },
                ]
            },

            {
                id: "discounts",

                view: "datatable",
                autoheight: true,
                select: true,

                pager:{
                    template:'{common.first()} {common.pages()}  {common.last()}',
                    container:'pager',
                    group:6,
                    size:10,
                },



                fixedRowHeight:false,
                on:{
                    onAfterLoad:function () {
                        $$("discounts").adjustRowHeight()
                    },
                    onDataUpdate:function () {
                       $$("discounts").adjustRowHeight()
                    }
                },

                columns: [
                    {
                        id: "id", header: "#", width:50
                    },
                    {
                        id: "title", header: "@lang('EvocmsDiscounts::main.col_title')", fillspace:3
                    },
                    {
                        id: "type", header: "@lang('EvocmsDiscounts::main.col_type')", fillspace:2,
                        options:[
                            { id:"product", value:"@lang('EvocmsDiscounts::main.type_product')" },
                            { id:"cart", value:"@lang('EvocmsDiscounts::main.type_cart')" },
                        ]
                    },
                    {
                        id: "rules", css:"rules", header: "@lang('EvocmsDiscounts::main.col_rules')", fillspace:4, template:function (item,$view,rules) {

                            let output = "";
                            for(let ruleId in rules){

                                let ruleValues = rules[ruleId];

                                let rule = rulesManager.getRule(ruleId);

                                output += "<p style='margin: 0;white-space: nowrap;'>"+rule.getDescriptionForTable(ruleValues)+"</p>";
                            }

                            return output;
                        },
                    },

                    {
                        id: "apply", header: "@lang('EvocmsDiscounts::main.col_apply')", fillspace:4, template:function(item,$view,applyValue){
                            let applyId = null;

                            for(let key in applyValue){
                                applyId = key;
                            }

                            let apply = appliesManager.getApply(applyId);


                            return apply.getDescriptionForTable(applyValue);
                        }
                    },
                    {
                        id: "discount_value", header: "@lang('EvocmsDiscounts::main.col_discount_value')", fillspace:2
                    },
                    {
                        id: "discount_type", header: "@lang('EvocmsDiscounts::main.col_discount_type')", fillspace:2,
                        options:[
                            { id:"amount", value:"@lang('EvocmsDiscounts::main.discount_type_amount')" },
                            { id:"percent", value:"@lang('EvocmsDiscounts::main.discount_type_percent')" },
                        ]
                    },
                    {
                        id: "active", header: "@lang('EvocmsDiscounts::main.col_active')", width:100,
                        template:"{common.checkbox()}"
                    },
                ],
                url: "{!! $moduleUrl !!}action=discounts-load",
                save:{
                    insert: "{!! $moduleUrl !!}action=discounts-add",
                    update: "{!! $moduleUrl !!}action=discounts-update",
                    delete: "{!! $moduleUrl !!}action=discounts-remove",
                }
            }

        ]
    })



</script>


</body>
</html>
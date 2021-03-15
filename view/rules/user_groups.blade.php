<script>

    let userGroupsRule = {

        getTypes:function(){
            return ['product','cart'];
        },

        getCaption: function () {
            return '@lang('EvocmsDiscounts::main.rule_user_groups_cation')';
        },

        getDescriptionForTable:function (rule) {
            return '<b>'+this.getCaption()+': </b>'+rule.value;
        },

        addRuleView: function (ruleInnerId, rule) {

            let viewId =  "user_groups_view";
            let inputIdSelect = "user_groups_input_select";




            let obj = {
                id:viewId,
                rows: [
                    {
                        name: "userGroups",
                        view: "select",
                        options: "{!! $moduleUrl !!}&action=rule-user-groups-load",
                        id: inputIdSelect,

                    },

                ]
            };
            $$(ruleInnerId).addView(obj);
            if(Object.keys(rule).length){
                $$(inputIdSelect).setValue(rule.id);
            }


            return true;

        },
        getData(store){

            var value = $$("user_groups_input_select").getValue();
            var sel = $$("user_groups_input_select").getInputNode();
            var text = sel.options[sel.selectedIndex].text;

            return {
                id:value,
                value:text
            };

        }

    };


    rulesManager.addRule('userGroups',userGroupsRule);

</script>
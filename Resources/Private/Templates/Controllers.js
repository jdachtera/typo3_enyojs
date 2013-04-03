<f:for each="{kindConfigs}" as="config">
enyo.kind(<![CDATA[{]]>
    name: '{config.name}',
    kind: 'Dachtera.Enyojs.Controller.ActionController',
    <f:for each="{config.actions}" as="action">
    /**
        {action.description}
    */
    {action.name}: function() <![CDATA[{]]>
        return this.initiateRequest('{action.name}', arguments);
    <![CDATA[},]]>
    </f:for>
    controller: '{config.controller}',
    pluginName: '{config.pluginName}',
    extensionName: '{config.extensionName}',
    actions: <![CDATA[{]]>
        <f:for each="{config.actions}" as="action" iteration="actionIterator">
        {action.name}: [<f:for each="{action.parameters}" as="parameter" iteration="paramIterator">
            '{parameter.name}'<f:if condition="{paramIterator.isLast}"><f:then></f:then><f:else>,</f:else></f:if>
        </f:for>]<f:if condition="{actionIterator.isLast}"><f:then></f:then><f:else>,</f:else></f:if>
        </f:for>
    <![CDATA[}]]>
<![CDATA[}]]>);
</f:for>
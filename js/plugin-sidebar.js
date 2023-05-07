( function ( wp ) {
    const registerPlugin = wp.plugins.registerPlugin;
    const PluginSidebar = wp.editPost.PluginSidebar;
    const el = wp.element.createElement;

    registerPlugin( 'my-plugin-sidebar', {
        render: function () {
            return el(
                PluginSidebar,
                {
                    name: 'my-plugin-sidebar',
                    icon: 'admin-post',
                    title: 'My plugin sidebar',
                },
                'Meta field'
            );
        },
    } );
} )( window.wp );

<?php

class WPKauRSP_UserRoles
{
    public function __construct()
    {
        $this->add_roles();
    }

    function add_roles()
    {
        $editor = get_role('editor');
        $author = get_role('author');

        $coordinator_capabilities_rsp = array(
            'read_rsp' => true,
            'create_rsp' => false,
            'edit_rsp' => true,
            'edit_others_rsp' => true,
            'publish_rsp' => true,
        );

        $researcher_capabilities_rsp = array(
            'read_rsp' => true,
            'create_rsp' => true,
            'edit_rsp' => true,
            'edit_others_rsp' => false,
            'publish_rsp' => false,
        );

        add_role('kau_coordinator', 'Department Coordinator', array_merge(
            $editor->capabilities,
            $coordinator_capabilities_rsp
        ));

        add_role('kau_researcher', 'Researcher', array_merge(
            $author->capabilities,
            $researcher_capabilities_rsp
        ));
    }
}

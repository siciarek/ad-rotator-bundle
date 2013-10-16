Usage
=====

SiciarekAdRotatorBundle is managed in SonataAdminBundle panel on page:

http://yourprojectdomain.tld/admin/dashboard


To display ads on your pages use twig helper.

.. code-block:: jinja

    display_ad(type, static)

Parameters:

    * ``type`` is number of ad type, visible in the first column of ``Ad types`` list (in admin panel), (default: 1).
    * ``static`` is to be used when you do not want rotate this ad dynamically after ``rotateAfter`` value in ``Ad type`` definition, (default: false).


Usage on page
-------------

.. code-block:: jinja

    {{ display_ad() }}
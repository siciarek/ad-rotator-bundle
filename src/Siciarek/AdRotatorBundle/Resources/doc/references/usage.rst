Usage
=====

``SiciarekAdRotatorBundle`` is to be managed in ``SonataAdminBundle`` panel on page:

http://yourprojectdomain.tld/admin/dashboard

To display radmized ads on your pages use twig helper.

.. code-block:: jinja

    display_ad(type, static)

Parameters:

    * ``type`` is a number of ad type, visible in the first column of ``Advertisement Type List`` list (in admin panel), (default: 1).
    * ``static`` is to be used when you **do not** want to rotate this ad dynamically after ``rotateAfter`` value in ``Ad type`` definition, (default: false).


To display specific ad on your pages use twig helper.

.. code-block:: jinja

    display_single_ad(id)

Parameters:

    * ``id`` is a number of specific ad, visible in the first column of ``Advertisement List`` (in admin panel).


``display_ad`` and ``display_single_ad`` can be called multiple times on one page.


Usage on page
-------------

No argument usage:

.. code-block:: jinja

    {{ display_ad() }}

is same as:

.. code-block:: jinja

    {{ display_ad(1, false) }}

``type`` value is given:

.. code-block:: jinja

    {{ display_ad(3) }}

is same as:

.. code-block:: jinja

    {{ display_ad(3, false) }}


``type`` and value for ``static`` is given:

.. code-block:: jinja

    {{ display_ad(3, true) }}


If no active ad of given ``type`` was found ``display_ad`` returns empty string.

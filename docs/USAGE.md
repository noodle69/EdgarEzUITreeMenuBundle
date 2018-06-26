# EdgarEzUITreeMenuBundle

## Exclude content with content type

To avoid listing content with specific content type, add to your ezplatform.yml this configuration

For example, to not display "User" content in "Content tree" :

```yaml
edgar_ez_ui_tree_menu:
    system:
        default:
            exclude_content_types:
                - user
```



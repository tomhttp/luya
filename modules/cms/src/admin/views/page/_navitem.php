<?php
use luya\cms\admin\Module;
use luya\helpers\Html;

?>
<script type="text/ng-template" id="recursion.html">
<h4 class="cmsadmin-container-title">{{placeholder.label}}</h4>
<div class="card">
    <div class="card-body">
        <div class="empty-placeholder" ng-if="placeholder.__nav_item_page_block_items.length == 0" dnd dnd-drag-disabled dnd-model="placeholder" dnd-isvalid="true" dnd-ondrop="dropItemPlaceholder(dragged,dropped,position)" dnd-css="{onDrag: 'empty-placeholder--is-dragging', onHover: 'empty-placeholder--drag-hover', onHoverTop: 'empty-placeholder--drag-top', onHoverMiddle: 'empty-placeholder--drag-middle', onHoverBottom: 'empty-placeholder--drag-bottom'}">Drop blocks here</div>
        <div ng-class="{'block-is-layout' : block.is_container}" ng-repeat="(key, block) in placeholder.__nav_item_page_block_items track by key" ng-controller="PageBlockEditController">
            <div class="block" ng-class="{ 'block-is-hidden': block.is_hidden == 1, 'block-is-virgin' : !block.is_dirty && isEditable() && block.is_dirty_dialog_enabled && !block.is_container, 'block-is-container': block.is_container, 'block-first': $first, 'block-last': $last }" dnd dnd-model="block" dnd-isvalid="true" dnd-disable-drag-middle dnd-ondrop="dropItem(dragged,dropped,position)" dnd-css="{onDrag: 'block--is-dragging', onHover: 'block--drag-hover', onHoverTop: 'block--drag-top', onHoverMiddle: 'block--drag-middle', onHoverBottom: 'block--drag-bottom'}">
                <div class="block-toolbar">
                    <div class="toolbar-item">
                        <i class="material-icons">{{block.icon}}</i>
                        <span>{{block.name}}</span>
                    </div>
                    <div class="toolbar-item ml-auto" ng-click="copyBlock()">
                        <button class="toolbar-button" tooltip tooltip-text="<?= Html::encode(Module::t('view_update_block_tooltip_copy'));?>" tooltip-position="top">
                            <i class="material-icons">content_copy</i>
                        </button>
                    </div>
                    <div class="toolbar-item" ng-click="toggleHidden()" ng-show="block.is_hidden==0">
                        <button class="toolbar-button" tooltip tooltip-text="<?= Html::encode(Module::t('view_update_block_tooltip_visible'));?>" tooltip-position="top">
                            <i class="material-icons">visibility</i>
                        </button>
                    </div>
                    <div class="toolbar-item" ng-click="toggleHidden()" ng-show="block.is_hidden==1">
                        <button class="toolbar-button" tooltip tooltip-text="<?= Html::encode(Module::t('view_update_block_tooltip_invisible'));?>" tooltip-position="top">
                            <i class="material-icons">visibility_off</i>
                        </button>
                    </div>
                    <div class="toolbar-item" ng-click="removeBlock()">
                        <button class="toolbar-button" tooltip tooltip-text="<?= Html::encode(Module::t('view_update_block_tooltip_delete'));?>" tooltip-position="top">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                    <div ng-show="isEditable()" ng-click="toggleEdit()" class="toolbar-item">
                        <button class="toolbar-button" tooltip tooltip-text="<?= Html::encode(Module::t('view_update_block_tooltip_edit'));?>" tooltip-position="top">
                            <i class="material-icons">edit</i>
                        </button>
                    </div>
                </div>
                <modal is-modal-hidden="modalHidden" modal-title="{{block.name}}">
                    <div ng-if="!modalHidden" class="card" ng-init="modalMode=1">
                        <div class="card-header" ng-show="block.cfgs.length > 0">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item" ng-click="modalMode=1">
                                    <a class="nav-link" ng-class="{'active' : modalMode==1}" ng-click="modalMode=1">Content</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" ng-class="{'active' : modalMode==2}" ng-click="modalMode=2">Config</a>
                                </li>
                            </ul>
                        </div>
                        <div  class="card-body">
                        <form class="block__edit" ng-submit="save()">
                            <div ng-if="modalMode==1" ng-repeat="field in block.vars" ng-hide="field.invisible" class="row">
                               <div class="col">
                                   <zaa-injector dir="field.type" options="field.options" fieldid="{{field.id}}" fieldname="{{field.var}}" initvalue="{{field.initvalue}}" placeholder="{{field.placeholder}}" label="{{field.label}}" model="data[field.var]"></zaa-injector>
                                </div>
                            </div>
                            <div ng-if="modalMode==2"  ng-repeat="cfgField in block.cfgs" ng-hide="cfgField.invisible" class="row">
                                <div class="col">
                                   <zaa-injector dir="cfgField.type"  options="cfgField.options" fieldid="{{cfgField.id}}" fieldname="{{cfgField.var}}" initvalue="{{cfgField.initvalue}}"  placeholder="{{cfgField.placeholder}}" label="{{cfgField.label}}"  model="cfgdata[cfgField.var]"></zaa-injector>
                               </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="material-icons left">done</i> Save</button>
                        </form>
                        </div>
                    </div>
                </modal>
                <div ng-if="!block.is_container" ng-click="toggleEdit()" class="block-front" ng-bind-html="renderTemplate(block.twig_admin, data, cfgdata, block, block.extras)"></div>
                <div ng-if="block.__placeholders.length" class="block-front">
                    <div class="row" ng-repeat="(inlineRowKey, row) in block.__placeholders track by inlineRowKey">
                        <div class="col-xl-{{placeholder.cols}}" ng-repeat="(placeholderInlineKey, placeholder) in row track by placeholderInlineKey" ng-include="'recursion.html'"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</script>
<?= $this->render('_navitem_settings'); ?>
<div class="cmsadmin-nav-tabs">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item nav-item-title">
            <span class="flag flag-{{lang.short_code}}">
                <span class="flag-fallback">{{lang.name}}</span>
            </span>
            <span>{{ item.title }}</span>
        </li>
    </ul>

    <ul class="nav nav-tabs" role="tablist" has-enough-space loading-condition="loaded" is-flex-box="true">
        <li class="nav-item nav-item-version" ng-repeat="versionItem in typeData" ng-if="item.nav_item_type==1 && isTranslated">
            <a class="nav-link" ng-class="{'active' : currentPageVersion == versionItem.id}" ng-click="switchVersion(versionItem.id);">
                <span>{{ versionItem.version_alias }}</span>
            </a>
        </li>
        <li class="nav-item nav-item-alternative" ng-show="isTranslated">
            <a class="nav-link" ng-click="itemSettingsOverlay=!itemSettingsOverlay;tab=3">
                <i class="material-icons">add_box</i>
            </a>
        </li>
    </ul>

    <ul class="nav nav-tabs cmsadmin-fallback-small" role="tablist">
        <li class="nav-item dropdown" ng-init="toggleVersionsDropdown=false" ng-class="{'show': toggleVersionsDropdown}">
            <a class="nav-link dropdown-toggle" role="button" ng-click="toggleVersionsDropdown = !toggleVersionsDropdown">Versions (#{{currentPageVersion}})</a>
            <div class="dropdown-menu" ng-class="{'show': toggleVersionsDropdown}">
                <button class="dropdown-item" ng-class="{'active' : currentPageVersion == versionItem.id}" ng-repeat="versionItem in typeData" ng-if="item.nav_item_type==1 && isTranslated">
                    <span>{{ versionItem.version_alias }} (#{{versionItem.id}})</span>
                </button>
                <div class="dropdown-divider"></div>
                <span class="dropdown-item" ng-click="itemSettingsOverlay=!itemSettingsOverlay;tab=3"><i class="material-icons">add_box</i> <span>Add version</span></span>
            </div>
        </li>
    </ul>

    <ul class="nav nav-tabs ml-auto flex-no-wrap" role="tablist">
        <li class="nav-item nav-item-alternative nav-item-icon ml-auto" ng-show="isTranslated">
            <a class="nav-link" ng-click="itemSettingsOverlay=!itemSettingsOverlay;tab=1">
                <i class="material-icons">edit</i>
            </a>
        </li>
        <li class="nav-item nav-item-alternative nav-item-icon" ng-show="isTranslated">
            <a ng-href="{{homeUrl}}preview/{{item.id}}?version={{currentPageVersion}}" target="_blank" class="nav-link" ng-show="!liveEditState">
                <i class="material-icons">open_in_new</i>
            </a>
            <a ng-click="openLiveUrl(item.id, currentPageVersion)" ng-show="liveEditState" class="nav-link">
                <i class="material-icons">open_in_new</i>
            </a>
        </li>
    </ul>
</div>

<div ng-if="!loaded">loading...</div>
<div class="cmsadmin-page" ng-if="isTranslated && loaded">
    <div class="row" ng-if="item.nav_item_type==1" ng-repeat="(key, row) in container.__placeholders track by key">
        <div class="col-xl-{{placeholder.cols}}" ng-repeat="(placeholderKey, placeholder) in row track by placeholderKey" ng-include="'recursion.html'" />
    </div>
    <div class="row" ng-if="item.nav_item_type==2">
        <?= Module::t('view_update_page_is_module'); ?>
    </div>
    <div class="row" ng-if="item.nav_item_type==3">
        <div ng-switch="typeData.type">
            <div ng-switch-when="1">
                <p><?= Module::t('view_update_page_is_redirect_internal'); ?></p>
            </div>
            <div ng-switch-when="2">
                <p><?= Module::t('view_update_page_is_redirect_external'); ?>.</p>
            </div>
        </div>
    </div>
</div>
<div class="cmsadmin-page" ng-show="!isTranslated && loaded">
    <!-- TODO -->
    <div class="alert alert-info"><?= Module::t('view_update_no_translations'); ?></div>
    <div ng-controller="CopyPageController">
        <h2><?= Module::t('view_index_add_page_from_language'); ?></h2>
        <p><?= Module::t('view_index_add_page_from_language_info'); ?></p>
        <p><button ng-click="loadItems()" ng-show="!isOpen" class="btn"><?= Module::t('view_index_yes'); ?></button></p>
        <div ng-show="isOpen">
            <hr />
            <ul>
                <li ng-repeat="item in items"><input type="radio" ng-model="selection" value="{{item.id}}"><label ng-click="select(item);">{{item.lang.name}} <i>&laquo; {{ item.title }} &raquo;</i></label></li>
            </ul>
            <div ng-show="itemSelection">
                <div class="row">
                    <div class="input input--text col s12">
                        <label class="input__label"><?= Module::t('view_index_page_title'); ?></label>
                        <div class="input__field-wrapper">
                            <input name="text" type="text" class="input__field" ng-change="aliasSuggestion()" ng-model="itemSelection.title" />
                        </div>
                    </div>
                <div class="row">
                </div>
                    <div class="input input--text col s12">
                        <label class="input__label"><?= Module::t('view_index_page_alias'); ?></label>
                        <div class="input__field-wrapper">
                            <input name="text" type="text" class="input__field" ng-model="itemSelection.alias" />
                        </div>
                    </div>
                </div>

                <button ng-click="save()" class="btn"><?= Module::t('view_index_page_btn_save'); ?></button>
            </div>
        </div>
    </div>
    <div ng-controller="CmsadminCreateInlineController">
        <h2><?= Module::t('view_index_add_page_empty'); ?></h2>
        <create-form data="data"></create-form>
    </div>
</div>
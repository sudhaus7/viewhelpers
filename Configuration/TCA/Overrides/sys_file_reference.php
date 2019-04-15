<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 01.10.18
 * Time: 13:55
 */
call_user_func(function () {
    global $TCA;
    $extKey = 'sudhaus7_viewhelpers';
    $languageFilePrefix = 'LLL:EXT:'.$extKey.'/Resources/Private/Language/locallang.xlf:';
    $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sudhaus7_viewhelpers']);
    if (isset($extConf['enableMediaPoster']) && $extConf['enableMediaPoster']) {
        $newColumns = [

            'tx_sudhaus7viewhelpers_posterimage' => [
                'displayCond' => 'USER:' . \SUDHAUS7\Sudhaus7Viewhelpers\Backend\Displayconds\Conditions::class . '->showPosterimageField',
                'exclude'     => 0,
                'label'       => $languageFilePrefix . 'field.posterimage.label',
                'config' => array(
                    'type' => 'input',
                    'size' => '50',
                    'max' => '256',
                    'eval' => 'trim',
                    'wizards' => array(
                        '_PADDING' => 2,
                        'link' => array(
                            'type' => 'popup',
                            'title' => 'LLL:EXT:cms/locallang_ttc.xml:header_link_formlabel',
                            'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                            'module' => array(
                                'name' => 'wizard_link',
                            ),
                            'params' => array(
                                'act'=>'file',
                                'allowedExtensions'=>'jpg,png',
                                'blindLinkOptions' => 'mail,page,external,url,ext',

                            ),
                            'P'=>[
                                'allowedExtensions'=>'jpg,png',
                            ],
                            'JSopenParams' => 'height=800,width=600,status=0,menubar=0,scrollbars=1',
                        ),
                    ),
                    'softref' => 'images',
                ),
            ],
        ];
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newColumns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file_reference', 'tx_sudhaus7viewhelpers_posterimage', '', '1');
        //\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToAllPalettesOfField( 'sys_file_reference', 'title', 'tx_sudhaus7viewhelpers_posterimage','after:title');
        TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('sys_file_reference', 'videoOverlayPalette', '--linebreak--,tx_sudhaus7viewhelpers_posterimage', 'before:title');
    }
});

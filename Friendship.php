<?php

namespace koolreport\codeigniter4;

use \koolreport\core\Utility;

trait Friendship
{
    public function __constructFriendship()
    {
        //assets folder
        $assets = Utility::get($this->reportSettings, "assets");
        if ($assets == null) {
            $document_root = Utility::getDocumentRoot();
            $script_folder = str_replace("\\", "/", realpath(dirname($_SERVER["SCRIPT_FILENAME"])));
            $asset_path = $script_folder . "/assets";
            $asset_url = Utility::strReplaceFirst($document_root, "", $script_folder) . "/assets";
            if (!is_dir($asset_path . "/koolreport_assets")) {
                if (!is_dir($asset_path)) {
                    mkdir($asset_path, 0755);
                }
                mkdir($asset_path . "/koolreport_assets", 0755);
            }

            $assets = array(
                "url" => $asset_url . "/koolreport_assets",
                "path" => $asset_path . "/koolreport_assets",
            );
            $this->reportSettings["assets"] = $assets;
        }

        // Usage:
        $config = \config('Database');
        $dbGroups = array_filter(array_keys((array)$config), function($key) {
            return $key !== 'filesPath' && $key !== 'defaultGroup';
        });
        $dbSources = array();
        foreach ($dbGroups as $name) {
            $dbSources[$name] = array(
                "class" => CIDataSource::class,
                "name" => $name,
            );
        }
        $dataSources = Utility::get($this->reportSettings, "dataSources", array());
        $this->reportSettings["dataSources"] = array_merge($dbSources, $dataSources);
    }
}
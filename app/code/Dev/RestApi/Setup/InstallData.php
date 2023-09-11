<?php
 
namespace Magetop\Helloworld\Setup;
 
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
 
class InstallData implements InstallDataInterface
{
 
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
 
        $tableName = $setup->getTable('magetop_blog');
        //Check for the existence of the table
        if ($setup->getConnection()->isTableExists($tableName) == true) {
            $data = [
                [
                    'title' => 'How to Speed Up Magento 2 Website',
                    'description' => 'Speeding up your Magento 2 website is very important, it affects user experience. Customers will feel satisfied when your site responds quickly',
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 1,
                ],
                [
                    'title' => 'Optimize SEO for Magento Website',
                    'description' => 'One of the important reasons why many people choose Magento 2 for their website is the ability to create SEO friendly',
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 1,
                ],
                [
                    'title' => 'Top 10 eCommerce Websites',
                    'description' => 'These are the websites of famous e-commerce corporations in the world. With very large revenue contributing to the world economy',
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 0,
                ],
            ];
            foreach ($data as $item) {
                //Insert data
                $setup->getConnection()->insert($tableName, $item);
            }
        }
        $setup->endSetup();
    }
}
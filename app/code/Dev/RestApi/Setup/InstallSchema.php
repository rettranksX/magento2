<?php
namespace Dev\RestApi\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('dev_restapi_tokens'))
            ->addColumn(
                'token_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Token ID'
            )
            ->addColumn(
                'token_value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Token Value'
            );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}

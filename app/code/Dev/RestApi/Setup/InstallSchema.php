<?php
namespace Dev\RestApi\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup; $installer->startSetup();
        $tableName = $installer->getTable('test_helloworld');
        if ($installer->getConnection()->isTableExists($tableName) != true)
        {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [

                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                        'default' => ''
                    ],
                    'Name'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                        'default' => ''
                    ],
                    'Description'
                )
                ->addColumn(
                   'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    [
                        'nullable' => false

                    ],

                    'Created At'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_SMALLINT,
                    null,
                    [
                        'nullable' => false,
                        'default' => '0'
                    ],
                    'Status'

                )
                ->setComment('Test Helloworld Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
                $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
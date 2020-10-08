<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200906112745 extends AbstractMigration
{
    /**
     * トランザクションをかけない
     *  - 後で変更したので、先に入れてたやつのプライマリーキー違反を無視するようにした
     */
    public function isTransactional() : bool
    {
        return false;
    }

    public function up(Schema $schema) : void
    {
        //----------------------------------------------
        // トップ対応
        //----------------------------------------------
        // カテゴリナビ部削除
        $this->addSql("delete from dtb_block_position where layout_id = 1 and block_id in (3, 10) ");
        // フッター部削除
        $this->addSql("delete from dtb_block_position where layout_id = 1 and section = 7");

        //----------------------------------------------
        // 下層ページ対応
        //----------------------------------------------
        // カテゴリナビ部削除、ロゴ部削除（ヘッダーに移動したので）
        $this->addSql("delete from dtb_block_position where layout_id = 2 and block_id in (3, 10) ");

        //----------------------------------------------
        // キーワード検索
        //----------------------------------------------
        // 検索追加
        $this->addSql("insert into dtb_block(block_name,file_name,use_controller,deletable,create_date,update_date,device_type_id,discriminator_type) values ('キーワード検索','search_keyword','1','0','2020/08/31 10:14:52','2020/08/31 10:14:52',10,'block')");
        // トップ
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (3,(SELECT id FROM dtb_block where file_name = 'search_keyword'),1,2,'blockposition')");
        // 下層ページ
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (3,(SELECT id FROM dtb_block where file_name = 'search_keyword'),2,2,'blockposition')");
    }

    public function down(Schema $schema) : void
    {
        //----------------------------------------------
        // トップ対応の削除
        //----------------------------------------------
        // カテゴリナビ部復活
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (3,3,1,2,'blockposition')");
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (3,10,1,1,'blockposition')");
        // フッター部復活
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (7,2,1,3,'blockposition')");
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (7,5,1,0,'blockposition')");
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (7,11,1,2,'blockposition')");
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (7,12,1,4,'blockposition')");
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (7,14,1,1,'blockposition')");

        //----------------------------------------------
        // 下層ページ対応の削除
        //----------------------------------------------
        // カテゴリナビ部復活、ロゴ部復活
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (3,3,2,3,'blockposition')");
        $this->addSql("insert into dtb_block_position(section,block_id,layout_id,block_row,discriminator_type) values (3,10,2,2,'blockposition')");

        //----------------------------------------------
        // キーワード検索削除
        //----------------------------------------------
        $this->addSql("delete from dtb_block_position where block_id = (SELECT id FROM dtb_block where file_name = 'search_keyword')");
        $this->addSql("delete from dtb_block where file_name = 'search_keyword'");
    }
}

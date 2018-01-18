class CreateMailings < ActiveRecord::Migration
    def up
    if table_exists?(:mailings)
      
      # update or modify columns of users table here accordingly.
    else
       create_table :mailings do |t|
      t.string :td
      t.string :serie
      t.string :numero
      t.string :ruc
      t.boolean :flag
      t.string :flag1

      t.timestamps null: false
    end
      # create table and dump the schema here
    end
  end
  
  def change
   
  end
end

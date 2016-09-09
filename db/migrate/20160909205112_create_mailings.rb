class CreateMailings < ActiveRecord::Migration
  def change
    create_table :mailings do |t|
      t.string :td
      t.string :serie
      t.string :numero
      t.string :ruc
      t.boolean :flag
      t.string :flag1

      t.timestamps null: false
    end
  end
end

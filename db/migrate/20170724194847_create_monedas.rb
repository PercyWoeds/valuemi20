class CreateMonedas < ActiveRecord::Migration
  def change
    create_table :monedas do |t|
      t.string :description
      t.string :descrip_short

      t.timestamps null: false
      
    end
  end
end

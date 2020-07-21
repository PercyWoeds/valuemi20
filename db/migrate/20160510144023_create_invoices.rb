class CreateInvoices < ActiveRecord::Migration
  def change
    create_table :invoices do |t|
      t.string :cliente
      t.date :fecha
      t.string :td
      t.string :serie
      t.string :numero
      t.decimal :preciocigv
      t.decimal :preciosigv
      t.float :cantidad
      t.float :vventa
      t.float :igv
      t.float :importe
      t.string :ruc
    


      t.timestamps null: false
    end
  end
end

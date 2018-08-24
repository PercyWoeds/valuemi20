class CreateNotes < ActiveRecord::Migration
  def change
    create_table :notes do |t|
      t.string :td
      t.datetime :fecha
      t.string :turno
      t.string :cod_emp
      t.string :caja
      
      t.string :serie
      t.string :numero
      
      t.string :cod_cli
      t.string :ruc
      t.string :placa
      t.string :odometro
      t.string :cod_prod
      t.float :cantidad
      t.float :precio_sigv
      t.float :precio
      t.float :vventa
      t.float :tax 
      t.float :importe
      
      t.float :igv
      t.string :fpago
      t.string :descrip
      t.string :tk_devol
      

      t.timestamps null: false
    end
  end
end

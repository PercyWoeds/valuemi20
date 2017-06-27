class AddNombreToInvoice < ActiveRecord::Migration
  def change
    add_column :invoices, :nombre, :text
  end
end

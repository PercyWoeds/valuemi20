class RemoveCodplaca10FromInvoices < ActiveRecord::Migration
  def change
    remove_column :invoices, :Codplaca10, :string
  end
end

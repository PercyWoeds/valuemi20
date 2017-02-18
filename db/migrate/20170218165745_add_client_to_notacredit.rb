class AddClientToNotacredit < ActiveRecord::Migration
  def change
    add_column :notacredits, :client_id, :integer
  end
end
